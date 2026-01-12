<?php

namespace App\Observers;

use App\Models\Expense;
use App\Models\Task\TaskAssignment;
use Illuminate\Support\Facades\DB;

class ExpenseObserver
{
    public function created(Expense $expense)
    {
        $this->recalculateBudgets($expense);
    }

    public function updated(Expense $expense)
    {
        $this->recalculateBudgets($expense);
    }

    public function deleted(Expense $expense)
    {
        $this->recalculateBudgets($expense);
    }

    protected function recalculateBudgets(Expense $expense): void
    {
        DB::transaction(function () use ($expense) {
            /** Task Assignment (Staff-specific) */
            $assignment = TaskAssignment::where('task_id', $expense->task_id)->where('user_id', $expense->user_id)->lockForUpdate()->first();

            if ($assignment) {
                $spent = Expense::where('task_id', $expense->task_id)->where('user_id', $expense->user_id)->sum('amount');

                $assignment->update([
                    'spent_amount'     => $spent,
                    'remaining_budget' => max(0, $assignment->assigned_budget - $spent),
                ]);
            }

            /** Task (Overall) */
            $taskSpent = Expense::where('task_id', $expense->task_id)->sum('amount');
            $task = $expense->task()->lockForUpdate()->first();

            $task->update([
                'spent_amount'     => $taskSpent,
                'remaining_budget' => max(0, $task->allocated_budget - $taskSpent),
            ]);
        });
    }
}
