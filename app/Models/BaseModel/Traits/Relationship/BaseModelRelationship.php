<?php
/**
 * Created by PhpStorm.
 * User: Yohana Samile
 * Date: 20/09/2025
 * Time: 19:22 PM
 */

namespace App\Models\BaseModel\Traits\Relationship;

use Spatie\Activitylog\LogOptions;

trait BaseModelRelationship
{
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName($this->getLogName())  // Set the log name here
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

}
