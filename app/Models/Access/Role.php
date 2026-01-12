<?php

namespace App\Models\Access;

use LaravelArchivable\Archivable;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use Archivable;

    protected $fillable = ['name', 'guard_name'];

    protected $searchable = [
        'name',
    ];

    protected $sortable = [
        'name' => 'asc',
    ];
}
