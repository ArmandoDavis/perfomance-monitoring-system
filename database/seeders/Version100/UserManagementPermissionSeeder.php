<?php

use App\Models\Access\Permission;
use App\Models\Access\Role;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class UserManagementPermissionSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('permissions');

        $permissions = [
            'user.view',
            'user.create',
            'user.update',
            'user.delete',
            'user.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = Role::findOrCreate('Admin');
        $hodRole   = Role::findOrCreate('Head of Department');

        $adminRole->givePermissionTo($permissions);

        $hodRole->givePermissionTo([
            'user.view',
        ]);

        $this->enableForeignKeys('permissions');

    }
}
