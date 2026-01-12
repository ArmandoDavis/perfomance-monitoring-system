<?php

namespace App\Models\System\Relationship;

use App\Models\SenderId\SenderOperatorStatus;
use App\Models\System\Code;

trait CodeValueRelationship
{
    public function senderOperatorStatus() {
        return $this->hasMany(SenderOperatorStatus::class, 'mno_cv_id');
    }

    public function code()
    {
        return $this->belongsTo(Code::class);
    }


    public function codes(){
        return $this->belongsToMany(Code::class, 'code_value_code', 'code_value_id', 'code_id');
    }
}
