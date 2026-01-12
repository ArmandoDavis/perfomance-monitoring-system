<?php

namespace App\Models\Task;

use App\Models\Access\User;
use App\Models\BaseModel\BaseModel;

class PerformanceScore extends BaseModel
{
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
