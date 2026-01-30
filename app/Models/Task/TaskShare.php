<?php

namespace App\Models\Task;

use App\Models\Access\User;
use App\Models\BaseModel\BaseModel;

class TaskShare extends BaseModel
{
    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function sharedWith() {
        return $this->belongsTo(User::class, 'shared_with');
    }

    public function sharedBy() {
        return $this->belongsTo(User::class, 'shared_by');
    }
}
