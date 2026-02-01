<?php

namespace App\Models\Access;

use App\Models\Attachment;
use App\Models\Department;
use App\Models\Expense;
use App\Models\System\CodeValue;
use App\Models\Task\PerformanceScore;
use App\Models\Task\Task;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use LaravelArchivable\Archivable;
use Overtrue\LaravelFavorite\Traits\Favoriter;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements AuditableContract, CanResetPasswordContract
{
    use Archivable, Auditable, CanResetPassword, Favoriter, HasFactory, HasRoles, Notifiable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getFirstName(): string
    {
        return Str::beforeLast($this->name, ' ');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    public function isHod(): bool
    {
        return $this->hasRole('Head of Department');
    }

    public function isNotAdmin(): bool
    {
        return !$this->isAdmin();
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function assignedTasks()
    {
        return $this->belongsToMany(Task::class, 'task_assignments')
            ->withPivot(['assigned_budget', 'spent_amount', 'remaining_budget'])
            ->withTimestamps();
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function performanceScores()
    {
        return $this->hasMany(PerformanceScore::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function userType()
    {
        return $this->belongsTo(CodeValue::class);
    }
}
