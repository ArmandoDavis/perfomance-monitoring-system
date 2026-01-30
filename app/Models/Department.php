<?php

namespace App\Models;

use App\Models\Access\User;
use App\Models\BaseModel\BaseModel;
use App\Models\Task\Task;

class Department extends BaseModel
{
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function getCanBeDeletedAttribute(): bool
    {
        if ($this->users()->exists()) {
            return false;
        }

        $protected = ['Management', 'ICT'];
        if (in_array($this->name, $protected)) {
            return false;
        }
        return true;
    }
}
