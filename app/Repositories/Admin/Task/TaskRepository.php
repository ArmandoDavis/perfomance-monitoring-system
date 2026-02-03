<?php
namespace App\Repositories\Admin\Task;

use App\Models\System\CodeValue;
use App\Models\Task\Task;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TaskRepository extends BaseRepository
{
    const MODEL = Task::class;
    protected $taskAssignmentRepository;

    public function __construct()
    {
        $this->taskAssignmentRepository = new TaskAssignmentRepository();
    }

    public function getAllForDt()
    {
        $user = auth()->user();
        $query = $this->query()
            ->select(['tasks.*', 'departments.name as department_name', 'users.name as creator_name', 'code_values.name as task_status'])
            ->leftJoin('departments', 'departments.id', '=', 'tasks.department_id')
            ->leftJoin('code_values', 'code_values.id', '=', 'tasks.status_cv_id')
            ->leftJoin('users', 'users.id', '=', 'tasks.created_by');

        if (!$user->hasRole('Admin')) {
            $query->where('tasks.department_id', $user->department_id);
        }
        return $query;
    }

    public function getActiveTasks()
    {
        return $this->queryIsActive();
    }

    public function getTasksToBeAddedExpense()
    {
        $status = CodeValue::getCodeValueByReference('SCS005');
        return $this->queryIsActive()->where('status_cv_id', $status->id)->get();
    }

    public function getHodTasksToBeAddedExpense()
    {
        $status = CodeValue::getCodeValueByReference('SCS005');
        return $this->queryIsActive()->where('status_cv_id', $status->id)->where('department_id', user()->department_id)->get();
    }

    public function getQueryUserTasks()
    {
        return $this->query()->whereHas('assignments', function ($q) {
            $q->where('user_id', user_id());
        });
    }

    public function getTasksByCreator($userId) {
        return Task::where('created_by', $userId)->latest()->get();
    }

    public function getTasksByDepartment($deptId) {
        return $this->query()->where('department_id', $deptId)->with('status')->latest()->get();
    }

    public function getUpcomingTasks($userId) {
        return $this->query()->where('created_by', $userId)
            ->where('is_active', true)
            ->whereNull('completed_at')
            ->where('end_date', '>=', now())
            ->orderBy('end_date', 'asc')
            ->get();
    }

    public function getTransferredTasks($userId) {
        return $this->query()->where('created_by', $userId)
            ->whereHas('status', function($q) {
                $q->where('name', 'Transferred');
            })->get();
    }

    public function store(array $input) {
        $status = CodeValue::getCodeValueByReference('SCS002');
        return DB::transaction(function() use($input, $status) {
            $task = $this->query()->create([
                'title' => $input['title'],
                'description' => $input['description'],
                'department_id' => $input['department_id'],
                'created_by' => user_id(),
                'allocated_budget' => $input['allocated_budget'],
                'spent_amount' => $input['spent_amount'] ?? 0,
                'remaining_budget' => $input['remaining_budget'] ?? 0,
                'status_cv_id' => $input['status_cv_id'] ?? $status->id,
                'start_date' => $input['start_date'],
                'end_date' => $input['end_date'],
                'progress_percent' => 0,
                'is_active' => $input['is_active'] ?? true,
            ]);

            /** assign task to users */
            if (isset($input['user_ids'])) {
                $this->taskAssignmentRepository->store($task, $input);
            }

            return $task;
        });
    }

    public function update(Model $task, array $input) {
        return DB::transaction(function() use($task, $input) {
            $task->update([
                'title' => $input['title'],
                'description' => $input['description'],
                'department_id' => $input['department_id'] ?? $task->department_id,
                'allocated_budget' => $input['allocated_budget'],
                'spent_amount' => $input['spent_amount'] ?? $task->spent_amount,
                'remaining_budget' => $input['remaining_budget'] ?? $task->remaining_budget,
                'status_cv_id' => $input['status_cv_id'],
                'start_date' => $input['start_date'],
                'end_date' => $input['end_date'],
            ]);

            /** assign task to users */
            if (isset($input['user_ids'])) {
                $this->taskAssignmentRepository->store($task, $input);
            }
        });
    }

    public function changeTaskStatus(Model $task, array $input)
    {
        return DB::transaction(function () use($task, $input) {
            $newStatusRef = match ($input['action']) {
                'activate', 'deactivate' => null,
                'start_progress' => 'SCS003',
                'submit_task' => 'SCS004',
                'complete' => 'SCS005',
                'reject' => 'SCS003',
                'undo_complete'  => 'SCS004', // Back to Submitted
                default          => throw new \Exception(__('Invalid action')),
            };
            if (in_array($input['action'], ['activate', 'deactivate'])) {
                return $this->changeStatus($task);
            }

            if ($newStatusRef) {
                if (in_array($input['action'], ['complete', 'reject'])) {
                    if (!auth()->user()->can('task.manage')) {
                        throw new \Exception(__('Insufficient permission.'));
                    }
                }
                $status = CodeValue::getCodeValueByReference($newStatusRef);
                if (!$status) {
                    throw new \Exception(__('Status code :ref not found in system', ['ref' => $newStatusRef]));
                }

                if ($input['action'] === 'undo_complete') {
                    $completedAt = Carbon::parse($task->completed_at);
                    if (!$task->completed_at || $completedAt->diffInHours(now()) > 48) {
                        throw new \Exception(__('You cannot undo a task completed more than 48 hours ago.'));
                    }
                }

                $updateData = ['status_cv_id' => $status->id];
                if ($input['action'] === 'complete') {
                    $updateData['completed_at'] = now();
                }
                else {
                    $updateData['completed_at'] = null;

                    if ($input['action'] === 'undo_complete') {
                        $updateData['progress_percent'] = 90;
                    }
                }
                return $task->update($updateData);
            }

            return false;
        });
    }

    public function markAsComplete(Model $task)
    {
        return DB::transaction(function () use($task) {
            return $task->update([
                'completed_at' => now()
            ]);
        });
    }

    public function undo(Model $task)
    {
        return DB::transaction(function () use($task) {
            return $task->update([
                'completed_at' => null
            ]);
        });
    }

    public function archive(Model $task)
    {
        return DB::transaction(function () use($task) {
            $task->archived_at = now();
            $task->save();
        });
    }

    public function delete(Model $task): ?bool
    {
        return DB::transaction(function () use($task) {
            return $task->delete();
        });
    }

    public function findById(int $id)
    {
        return $this->find($id);
    }

    public function findByUuid($uid)
    {
        return $this->findByUid($uid);
    }
}
