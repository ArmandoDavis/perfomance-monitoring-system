<?php

use App\Models\Access\Permission;
use App\Models\Access\Role;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class TaskPermissionSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('permissions');

        $permissions = [
            'task.view',
            'task.create',
            'task.update',
            'task.delete',
            'task.manage',
            'task.assign',
            'task.evaluate',
            'performance.evaluate'
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $adminRole = Role::findOrCreate('Admin');
        $hodRole   = Role::findOrCreate('Head of Department');
        $staffRole = Role::findOrCreate('Staff');

        $adminRole->givePermissionTo(Permission::all());

        $hodRole->givePermissionTo([
            'task.view',
            'task.create',
            'task.update',
            'task.manage',
            'task.assign',
            'task.evaluate',
            'performance.evaluate'
        ]);

        $staffRole->givePermissionTo([
            'task.view',
            'task.create',
            'task.update',
        ]);

        $this->enableForeignKeys('permissions');
    }
}
