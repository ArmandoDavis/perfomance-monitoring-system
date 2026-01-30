<?php

namespace App\Observers;

use App\Models\Task\TaskAssignment;
use App\Models\Task\TaskProgressLog;

class TaskAssignmentObserver
{
    public function created(TaskAssignment $assignment)
    {
        $task = $assignment->task;
        if ($task && $task->progress_percent == 0) {
            $task->update(['progress_percent' => 10]);
        }

        TaskProgressLog::create([
            'task_id' => $assignment->task_id,
            'user_id' => $assignment->user_id,
            'status' => 'Assigned',
            'comment' => 'Worker assigned to start task',
            'logged_at' => now()
        ]);
    }
}
