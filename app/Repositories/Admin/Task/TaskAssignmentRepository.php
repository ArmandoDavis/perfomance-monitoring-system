<?php
namespace App\Repositories\Admin\Task;

use App\Models\Access\User;
use App\Models\Task\TaskAssignment;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaskAssignmentRepository extends BaseRepository
{
    const MODEL = TaskAssignment::class;

    public function getAllForDt()
    {
        return $this->query()
            ->select([
                'task_assignments.*',
                'tasks.title as task_title',
                'users.name as user_name',
            ])
            ->leftJoin('tasks', 'tasks.id', '=', 'task_assignments.task_id')
            ->leftJoin('users', 'users.id', '=', 'task_assignments.user_id');
    }

    public function getActiveAssignments()
    {
        return $this->queryIsActive();
    }

    public function store(Model $task, array $input) {
        return DB::transaction(function() use($task, $input) {
            foreach ($input['user_ids'] as $userId) {
                $exists = $this->query()->where('task_id', $task->id)->where('user_id', $userId)->exists();
                if ($exists) {
                    throw new \Exception(__('User :name is already assigned to this task', [
                        'name' => optional(User::find($userId))->name ?? $userId
                    ]));
                }
                $this->query()->create([
                    'task_id' => $task->id,
                    'user_id' => $userId,
                    'assigned_budget'  => $input['assigned_budget'] ?? 0,
                    'remaining_budget' => $input['assigned_budget'] ?? 0,
                    'is_active'  => $input['is_active'] ?? true,
                ]);
            }
        });
    }

    public function update(Model $assignment, array $input)
    {
        return DB::transaction(function () use ($assignment, $input) {
            return $assignment->update([
                'assigned_budget' => $input['assigned_budget'],
                'is_active'  => $input['is_active'],
            ]);
        });
    }

    public function changeTaskStatus(Model $task, array $input)
    {
        return DB::transaction(function () use($task, $input) {
            return match ($input['action']) {
                'activate'   => $this->changeStatus($task, true),
                'deactivate' => $this->changeStatus($task, false),
                default      => throw new \Exception(__('alert.invalid_action')),
            };
        });
    }

    public function archive(Model $assignment)
    {
        return DB::transaction(function () use ($assignment) {
            return $assignment->update([
                'archived_at' => now(),
            ]);
        });
    }

    public function delete(Model $assignment): ?bool
    {
        return DB::transaction(function () use ($assignment) {
            return $assignment->delete();
        });
    }
}
