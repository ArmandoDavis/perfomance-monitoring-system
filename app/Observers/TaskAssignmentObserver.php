<?php

namespace App\Observers;

use App\Models\Task\TaskAssignment;
use App\Models\Task\TaskProgressLog;

class TaskAssignmentObserver
{
    public function created(TaskAssignment $assignment)
    {
        TaskProgressLog::create([
            'task_id' => $assignment->task_id,
            'user_id' => $assignment->user_id,
            'status' => 'Assigned',
            'comment' => 'User assigned to task',
            'logged_at' => now()
        ]);
    }
}
