<?php

namespace App\Models\Task;

use App\Models\Access\User;
use App\Models\Department;
use App\Models\System\CodeValue;
use App\Models\BaseModel\BaseModel;

class TaskShare extends BaseModel
{
    public function task() {
        return $this->belongsTo(Task::class);
    }

    public function sharedWithUser() {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }

    public function sharedWithDepartment() {
        return $this->belongsTo(Department::class, 'shared_with_department_id');
    }

    public function sharedBy() {
        return $this->belongsTo(User::class, 'shared_by');
    }

    public function accessLevel() {
        return $this->belongsTo(CodeValue::class, 'access_level_cv_id');
    }
}
