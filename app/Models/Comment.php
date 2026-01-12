<?php

namespace App\Models;

use App\Models\Access\User;
use App\Models\BaseModel\BaseModel;
use App\Models\Task\Task;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends BaseModel
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
