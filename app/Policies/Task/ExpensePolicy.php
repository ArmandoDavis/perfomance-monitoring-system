<?php

namespace App\Policies\Task;

use App\Models\Access\User;
use App\Models\Expense;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExpensePolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
    }

    public function view(User $user, Expense $expense)
    {
        return $user->id === $expense->created_by || $user->can('expense.view');
    }

    public function update(User $user, Expense $expense)
    {
        if ($user->id === $expense->created_by && $expense->status->reference == 'EXS001') {
            return true;
        }

        return $user->can('expense.update');
    }

    public function manage(User $user, Expense $expense)
    {
        return $user->can('expense.manage') && $user->department_id === $expense->task->department_id;
    }

    public function delete(User $user, Expense $expense)
    {
        if ($expense->status->reference != 'EXS001') {
            return false;
        }
        return $user->id === $expense->created_by || $user->can('expense.delete');
    }
}
