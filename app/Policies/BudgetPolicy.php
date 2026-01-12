<?php

namespace App\Policies;

use App\Models\Access\User;
use App\Models\Task\Task;

class BudgetPolicy
{
    public function allocate(User $user, Task $task): bool
    {
        return $user->hasRole('Head of Department') && $task->allocated_budget === null;
    }

    public function submitExpense(User $user, Task $task): bool
    {
        return $user->hasRole('Staff') && $task->assignedTo($user) && $task->remaining_budget > 0;
    }

    public function approveExpense(User $user): bool
    {
        return $user->hasRole('Head of Department');
    }

    public function view(User $user, Task $task): bool
    {
        return $user->hasRole('Admin') || $user->hasRole('Head of Department') || $task->assignedTo($user);
    }
}
