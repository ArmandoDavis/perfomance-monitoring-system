<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtask extends Model
{
    //
    protected $fillable = [
        'task_id',
        'name',
        'description',
        'budget',
        'status',
        'created_by',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
