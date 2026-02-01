<?php

namespace App\Models;

use App\Models\Access\User;
use App\Models\BaseModel\BaseModel;
use App\Models\Task\Task;
use Illuminate\Support\Facades\Storage;

class Expense extends BaseModel
{
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function receipt()
    {
        return $this->belongsTo(Attachment::class, 'receipt_path_id');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable')->where('is_active', true);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
