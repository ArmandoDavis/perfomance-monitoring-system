<?php

namespace App\Models;

use App\Models\Access\User;
use App\Models\BaseModel\BaseModel;
use App\Models\Task\Task;

class Report extends BaseModel
{
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
