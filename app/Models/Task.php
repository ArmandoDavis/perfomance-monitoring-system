<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Department;
use App\Models\TaskBudget;
use App\Models\Subtask;

class Task extends Model
{
    //
    protected $fillable = [
    'title',
    'description',
    'department_id',
    'start_date',
    'due_date',
    'status',
    'created_by',
];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function budgets()
    {
    return $this->hasMany(TaskBudget::class);
    }
    public function subtasks(): HasMany
    {
    return $this->hasMany(Subtask::class);
    }
    public function getTotalBudgetAttribute()
    {
    return $this->subtasks()->sum('budget');
    }      
}
