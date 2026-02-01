<?php
namespace App\Observers;

use App\Models\System\CodeValue;
use App\Models\Task\Task;
use App\Models\Task\TaskProgressLog;

class TaskObserver
{
    public function saving(Task $task)
    {
        if (app()->runningInConsole()) {
            return;
        }

        // Budget Calculation
        if ($task->isDirty('allocated_budget') || $task->isDirty('spent_amount')) {
            if ($task->allocated_budget < $task->spent_amount) {
                $task->spent_amount = $task->allocated_budget;
            }
            $task->remaining_budget = $task->allocated_budget - $task->spent_amount;
        }

        // Automated Progress Logic (Real-world mapping)
        if ($task->isDirty('status_cv_id') && !$task->isDirty('progress_percent')) {
            $statusName = strtolower(CodeValue::where('id', $task->status_cv_id)->value('name'));

            $task->progress_percent = match($statusName) {
                'pending', 'created' => 0,
                'in progress', 'active' => 50, // Default start point
                'on hold' => 30,
                'completed', 'finished' => 100,
                default => $task->progress_percent,
            };

            if ($task->progress_percent == 100) {
                $task->completed_at = now();
            }
        }
    }

    public function created(Task $task)
    {
        $this->logAction($task, 'Created', 'Task created initially');
    }

    public function updated(Task $task)
    {
        if ($task->wasChanged('status_cv_id')) {
            $statusName = optional($task->status)->name ?? 'Updated';
            $this->logAction($task, $statusName, "Status changed to {$statusName}");
        }
    }

    protected function logAction(Task $task, string $status, ?string $comment = null)
    {
        TaskProgressLog::create([
            'task_id'   => $task->id,
            'user_id'   => user_id() ?? $task->created_by,
            'status'    => $status,
            'comment'   => $comment,
            'logged_at' => now()
        ]);
    }

}
