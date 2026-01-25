<?php
namespace App\Repositories\Frontend\Task;

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
            ->where('tasks.is_active', 1)
            ->select([
                'tasks.uuid',
                'tasks.title',
                'tasks.start_date',
                'tasks.end_date',
                'tasks.completed_at',
                'tasks.allocated_budget',
                'tasks.spent_amount',
                'tasks.status_cv_id',
                'tasks.created_by',
                'departments.name as department_name',
                'users.name as creator_name',
                'code_values.name as task_status',
                'code_values.reference as status_ref',
            ])
            ->leftJoin('task_assignments', 'task_assignments.task_id', '=', 'tasks.id')
            ->leftJoin('departments', 'departments.id', '=', 'tasks.department_id')
            ->leftJoin('users', 'users.id', '=', 'tasks.created_by')
            ->leftJoin('code_values', 'code_values.id', '=', 'tasks.status_cv_id')
            ->where('task_assignments.user_id', user_id())
            ->whereNull('tasks.deleted_at')
            ->whereNull('tasks.archived_at')
            ->groupBy([
                'tasks.id',
                'tasks.title',
                'tasks.start_date',
                'tasks.end_date',
                'tasks.completed_at',
                'tasks.allocated_budget',
                'tasks.spent_amount',
                'tasks.status_cv_id',
                'tasks.created_by',
                'departments.name',
                'users.name',
                'code_values.name',
                'code_values.reference',
            ]);
    }


    public function getQueryUserTasks()
    {
        return $this->query()->whereHas('assignments', function ($q) {
            $q->where('user_id', user_id());
        });
    }

    public function updateStatus(Model $task, $reference)
    {
        return DB::transaction(function () use($task, $reference) {
            $status = CodeValue::getCodeValueByReference($reference);
            if ($reference == "SCS005") {
                return $task->update([
                    'status_cv_id' => $status->id,
                    'completed_at' => now()
                ]);
            }

            return $task->update([
                'status_cv_id' => $status->id
            ]);
        });
    }

    public function undo(Model $task)
    {
        return DB::transaction(function () use($task) {
            $complete = CodeValue::getCodeValueByReference('SCS002');
            return $task->update([
                'status_cv_id' => $complete->id,
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
}
