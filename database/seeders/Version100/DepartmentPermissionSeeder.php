<?php

use App\Models\Access\Permission;
use App\Models\Access\Role;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class DepartmentPermissionSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('permissions');

        Permission::findOrCreate('department.manage');

        $admin = Role::findOrCreate('Admin');
        $hod = Role::findOrCreate('Head of Department');

        $admin->givePermissionTo('department.manage');

        $hod->givePermissionTo('department.manage');
        $this->enableForeignKeys('permissions');

    }
}
