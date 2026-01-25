<?php

namespace App\Observers;

use App\Models\Task\Task;
use App\Models\Task\TaskProgressLog;

class TaskObserver
{
    public function updating(Task $task)
    {
        if ($task->isDirty('allocated_budget')) {

            if ($task->allocated_budget < $task->spent_amount) {
                throw new \Exception('Allocated budget cannot be less than already spent amount');
            }

            $task->remaining_budget = $task->allocated_budget - $task->spent_amount;
        }
    }

    /** progress logs  */
    public function created(Task $task)
    {
        $this->logForAllAssignees($task, $task->status->name ?? 'Created', 'Task created');
    }

    public function updated(Task $task)
    {
        // Only log when status changes
        if (!$task->wasChanged('status_cv_id')) {
            return;
        }

        $statusName = optional($task->status)->name ?? 'Updated';
        $this->logForAllAssignees($task, $statusName, "Status changed to {$statusName}");
    }

    protected function logForAllAssignees(Task $task, string $status, ?string $comment = null)
    {
        foreach ($task->assignments as $user) {
            TaskProgressLog::create([
                'task_id' => $task->id,
                'user_id' => $user->id,
                'status'  => $status,
                'comment' => $comment,
                'logged_at' => now()
            ]);
        }
    }
}
