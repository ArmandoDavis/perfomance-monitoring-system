<?php

use App\Models\Access\Permission;
use App\Models\Access\Role;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class ExpensePermissionSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('permissions');

        $permissions = [
            'expense.view',
            'expense.create',
            'expense.update',
            'expense.delete',
            'expense.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        $admin = Role::findOrCreate('Admin');
        $hod = Role::findOrCreate('Head of Department');
        $staff = Role::findOrCreate('Staff');

        $admin->givePermissionTo($permissions);

        $hod->givePermissionTo([
            'expense.view',
            'expense.manage',
            'expense.create',
        ]);

        $staff->givePermissionTo([
            'expense.view',
            'expense.create',
        ]);
        $this->enableForeignKeys('permissions');
    }
}
