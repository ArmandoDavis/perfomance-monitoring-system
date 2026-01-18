<?php

namespace App\Models;

use App\Models\Access\User;
use App\Models\BaseModel\BaseModel;
use App\Models\Task\Task;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends BaseModel
{
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
