<?php

namespace App\Policies;

use App\Models\Access\User;
use App\Models\System\CodeValue;
use App\Models\Task\Task;
use Illuminate\Support\Facades\Log;

class TaskPolicy
{
    public function before(User $user, string $ability)
    {
//        logger()->info('TaskPolicy before hit', [
//            'user_id' => $user->id,
//            'ability' => $ability,
//        ]);

        if ($user->hasRole('Admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
//        Log::info('TaskPolicy viewAny hit', [
//            'user_id' => $user->id
//        ]);
        return $user->can('task.view_all') || $user->can('task.view_assigned');
    }

    public function view(User $user, Task $task): bool
    {
        if ($user->hasRole('Head of Department')) {
            return $task->department_id === $user->department_id;
        }

        return $task->assignedTo($user);
    }

    public function create(User $user): bool
    {
        return $user->can('task.create');
    }

    public function assign(User $user, Task $task): bool
    {
        $backlog = CodeValue::getCodeValueByReference('SCS001');
        return $user->hasRole('Head of Department') && $task->status_cv_id === $backlog->id;
    }

    public function updateStatus(User $user, Task $task): bool
    {

        $todo = CodeValue::getCodeValueByReference('SCS002');
        $inProgress = CodeValue::getCodeValueByReference('SCS003');

        return $user->hasRole('Staff') && $task->assignedTo($user) && in_array($task->status_cv_id, [$todo, $inProgress->id]);
    }

    public function submit(User $user, Task $task): bool
    {
        $inProgress = CodeValue::getCodeValueByReference('SCS003');
        return $user->hasRole('Staff') && $task->assignedTo($user) && $task->status_cv_id === $inProgress->id;
    }

    public function approve(User $user, Task $task): bool
    {
        $submitted = CodeValue::getCodeValueByReference('SCS002');

        return $user->hasRole('Head of Department') && $task->status_cv_id === $submitted->id;
    }

    public function reject(User $user, Task $task): bool
    {
        return $this->approve($user, $task);
    }

    public function evaluate(User $user, Task $task)
    {
        return $user->can('task.evaluate');
    }

    public function rollback(User $user, Task $task): bool
    {
        $submitted = CodeValue::getCodeValueByReference('SCS002');
        $done = CodeValue::getCodeValueByReference('SCS002');
        return $user->hasRole('Head of Department') && in_array($task->status_cv_id, [$submitted->id, $done->id]);
    }
}
