<?php
namespace App\Observers;

use App\Models\Task\TaskAssignment;

class TaskAssignmentObserver
{
    /** Before create */
    public function creating(TaskAssignment $assignment): void
    {
        $assignment->remaining_budget = $assignment->assigned_budget - ($assignment->spent_amount ?? 0);
    }

    /**  After create */
    public function created(TaskAssignment $assignment): void
    {
        $this->syncTaskBudget($assignment);
    }

    /**  Before update  */
    public function updating(TaskAssignment $assignment): void
    {
        if ($assignment->isDirty(['assigned_budget', 'spent_amount'])) {
            $assignment->remaining_budget = $assignment->assigned_budget - $assignment->spent_amount;
        }
    }

    /** After update */
    public function updated(TaskAssignment $assignment): void
    {
        $this->syncTaskBudget($assignment);
    }

    /**  After delete (important!) */
    public function deleted(TaskAssignment $assignment): void
    {
        $this->syncTaskBudget($assignment);
    }

    /** Sync task budget safely  */
    protected function syncTaskBudget(TaskAssignment $assignment): void
    {
        $task = $assignment->task;
        if (!$task) {
            return;
        }

        $allocated = $task->assignments()->sum('assigned_budget');
        $spent = $task->assignments()->sum('spent_amount');

        $task->update([
            'allocated_budget' => $allocated,
            'spent_amount' => $spent,
            'remaining_budget' => $allocated - $spent,
        ]);
    }
}

