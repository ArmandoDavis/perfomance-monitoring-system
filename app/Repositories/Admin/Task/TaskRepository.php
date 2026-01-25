<?php
namespace App\Repositories\Admin\Task;

use App\Models\System\CodeValue;
use App\Models\Task\Task;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaskRepository extends BaseRepository
{
    const MODEL = Task::class;

    public function getAllForDt()
    {
        return $this->query()
            ->select(['tasks.*', 'departments.name as department_name', 'users.name as created_by',])
            ->leftJoin('departments', 'departments.id', '=', 'tasks.department_id')
            ->leftJoin('users', 'users.id', '=', 'tasks.created_by');
    }

    public function getActiveTasks()
    {
        return $this->queryIsActive();
    }

    public function getQueryUserTasks()
    {
        return $this->query()->whereHas('assignments', function ($q) {
            $q->where('user_id', user_id());
        });
    }

    public function store(array $input) {
        return DB::transaction(function() use($input) {
            return $this->query()->create([
                'title' => $input['title'],
                'description' => $input['description'],
                'department_id' => $input['department_id'],
                'created_by' => user_id(),
                'allocated_budget' => $input['allocated_budget'],
                'spent_amount' => $input['spent_amount'] ?? 0,
                'remaining_budget' => $input['remaining_budget'] ?? 0,
                'status_cv_id' => $input['status_cv_id'],
                'start_date' => $input['start_date'],
                'end_date' => $input['end_date'],
                'is_active' => isset($input['is_active']),
            ]);
        });
    }

    public function update(Model $task, array $input) {
        return DB::transaction(function() use($task, $input) {
            return $task->update([
                'title' => $input['title'],
                'description' => $input['description'],
                'department_id' => $input['department_id'],
                'allocated_budget' => $input['allocated_budget'],
                'spent_amount' => $input['spent_amount'],
                'remaining_budget' => $input['remaining_budget'],
                'status_cv_id' => $input['status_cv_id'],
                'start_date' => $input['start_date'],
                'end_date' => $input['end_date'],
                'is_active' => $input['is_active'],
            ]);
        });
    }

    public function changeTaskStatus(Model $task, array $input)
    {
        return DB::transaction(function () use($task, $input) {
            $complete = CodeValue::getCodeValueByReference('SCS005');

            return match ($input['action']) {
                'activate'   => $this->changeStatus($task, true),
                'deactivate' => $this->changeStatus($task, false),
                'complete' => $task->update(['status_cv_id' => $complete->id]),
                default      => throw new \Exception(__('Invalid action')),
            };
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
            $this->renamingSoftDelete($task, 'name');
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
