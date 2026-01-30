<?php

namespace App\Policies\Task;

use App\Models\Access\User;
use App\Models\Task\Task;

class TaskPolicy
{
    public function before(User $user, string $ability)
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('task.view');
    }

    public function view(User $user, Task $task): bool
    {
        if (!$user->can('task.view')) return false;

        if ($user->can('task.manage') && $task->department_id === $user->department_id) {
            return true;
        }

        if ($task->created_by === $user->id || $task->assignedTo($user)) {
            return true;
        }

        if ($task->sharedWith($user->id)) {
            return true;
        }

        if ($task->is_transferred && $task->transferred_to === $user->id) {
            return true;
        }
        return false;
    }

    public function create(User $user): bool
    {
        return $user->can('task.create');
    }

    public function update(User $user, Task $task): bool
    {
        if (!$user->can('task.update')) return false;
        if ($task->status->reference === 'SCS005') return false;
        return $task->created_by === $user->id || $user->can('task.manage');
    }

    public function assign(User $user, Task $task): bool
    {
        return $user->can('task.manage') && $task->department_id === $user->department_id;
    }

    public function updateStatus(User $user, Task $task): bool
    {
        if ($task->assignedTo($user)) {
            return in_array($task->status->reference, ['SCS002', 'SCS003']);
        }
        return $user->can('task.manage') && $task->department_id === $user->department_id;
    }

    public function manage(User $user, Task $task): bool
    {
        return $user->can('task.manage') && $task->department_id === $user->department_id;
    }

    public function delete(User $user, Task $task): bool
    {
        if (!$user->can('task.delete')) return false;
        if (!in_array($task->status->reference, ['SCS001', 'SCS002'])) return false;
        return $task->created_by === $user->id || $user->can('task.manage');
    }

    public function transfer(User $user, Task $task): bool
    {
        return ($user->can('task.manage') && $task->department_id === $user->department_id) || $task->created_by === $user->id;
    }

    public function share(User $user, Task $task): bool
    {
        return $task->assignedTo($user) || $task->created_by === $user->id || $user->can('task.manage');
    }

    public function rollback(User $user, Task $task): bool
    {
        if (!$user->can('task.manage')) return false;
        $hours = $task->completed_at ? $task->completed_at->diffInHours(now()) : 999;
        return $task->status->reference === 'SCS005' && $hours <= 48;
    }
}
