<?php

namespace App\Models\Access;

use Illuminate\Support\Str;
use LaravelArchivable\Archivable;
use Spatie\Permission\Models\Role as SpatieRole;
 use Illuminate\Database\Eloquent\SoftDeletes;

 class Role extends SpatieRole
{
    use Archivable, SoftDeletes;

    protected $fillable = ['name', 'guard_name'];

    protected $searchable = [
        'name',
    ];

    protected $sortable = [
        'name' => 'asc',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

     public function getCanBeDeletedAttribute(): bool
     {
         if ($this->is_system_defined) {
             return false;
         }

         if ($this->users()->exists()) {
             return false;
         }
         return true;
     }
}
