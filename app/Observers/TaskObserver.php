<?php

namespace App\Observers;

use App\Models\Task\Task;

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
}
