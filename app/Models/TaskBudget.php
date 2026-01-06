<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskBudget extends Model
{
    //
    protected $fillable = [
        'task_id',
        'user_id',
        'allocated_amount',
        'description',
        'created_by',
        'approved_at',
    ];
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
