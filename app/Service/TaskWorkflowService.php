<?php

namespace App\Service;

use App\Models\Task\Task;

class TaskWorkflowService
{
    public static function transition(Task $task, string $to)
    {
        $allowed = [
            'Todo' => ['In progress'],
            'In progress' => ['Submitted'],
            'Submitted' => ['Done', 'In progress'],
            'Done' => ['Deployed'],
        ];

        if (!in_array($to, $allowed[$task->status] ?? [])) {
            redirect()->back()->with('error', 'Invalid state transition');
        }

        $task->update(['status' => $to]);
    }
}

