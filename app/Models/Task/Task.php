<?php

namespace App\Models\Task;

use App\Models\Access\User;
use App\Models\BaseModel\BaseModel;
use App\Models\Comment;
use App\Models\Department;
use App\Models\Expense;
use App\Models\Report;
use App\Models\System\CodeValue;
use LaravelArchivable\Archivable;

class Task extends BaseModel
{
    use Archivable;

    public function getCanBeDeletedAttribute(): bool
    {
        // Assuming 'SCS002' is 'Todo'
        $todo = CodeValue::getCodeValueByReference('SCS002');
        return $this->status_cv_id === $todo->id;
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignments()
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function progressLogs()
    {
        return $this->hasMany(TaskProgressLog::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function performanceScores()
    {
        return $this->hasMany(PerformanceScore::class);
    }

    public function assignedTo(User $user): bool
    {
        return $this->assignees()->where('user_id', $user->id)->exists();
    }

    public function status()
    {
        return $this->belongsTo(CodeValue::class, 'status_cv_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('user');
    }

}
