<?php
/**
 * Created by PhpStorm.
 * User: Yohana Samile
 * Date: 20/09/2025
 * Time: 19:22 PM
 */

namespace App\Models\BaseModel\Traits\Attribute;

use Carbon\Carbon;

trait BaseModelAttribute
{
    public function getCreatedAtFormattedAttribute()
    {
        return short_date_format($this->created_at);
    }

    protected static function booted()
    {
        static::creating(function ($column) {
            $column->uuid = str_unique();
        });
    }

    /**
     * @return string
     * change status
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->isactive)
        {
            case 1 :
                return "<span class='badge badge-success text-center'>" .  trans('label.yes') .  "</span>";
                break;
            case 0:
                return "<span class='badge badge-info text-center'>" .  trans('label.no') .  "</span>";
                break;
        }
    }
}
