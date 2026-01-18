<?php

namespace App\Models\Task;

use App\Models\Access\User;
use App\Models\Attachment;
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
        $todo = CodeValue::getCodeValueByReference('SCS002');
        return $this->status_cv_id === $todo->id && !$this->expenses()->exists();
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
        /** act like pivot table task_user */
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

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable')->where('is_active', true);
    }

    public function documents()
    {
        return $this->hasManyThrough(Attachment::class,
            Expense::class,
            'task_id',           // FK on expenses table
            'id',               // PK on attachments table
            'id',               // PK on tasks table
            'receipt_path_id'  // FK on expenses pointing to attachments
        )->whereNull('attachments.deleted_at');
    }
}
