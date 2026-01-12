<?php
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;
    public function run(): void
    {
        $this->disableForeignKeys('roles');
        $this->disableForeignKeys('permissions');
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            /** Users */
            'user.view',
            'user.create',
            'user.update',
            'user.deactivate',
            'user.activate',
            'user.reset_password',

            /** Roles & Permissions */
            'role.view',
            'role.create',
            'role.update',
            'role.delete',
            'permission.view',
            'permission.assign',

            /** Tasks */
            'task.view_all',
            'task.view_assigned',
            'task.create',
            'task.update',
            'task.assign',
            'task.reassign',
            'task.delete',
            'task.rollback',
            'task.approve',
            'task.reject',
            'task.comment',
            'task.close',

            /** Task Progress */
            'task.progress.update',
            'task.progress.submit',
            'task.progress.rollback',
            'task.emerging_activity.add',
            'task.timelog.add',

            /** Budget */
            'budget.view_all',
            'budget.allocate',
            'budget.assign_to_staff',
            'budget.submit',
            'budget.update',
            'budget.view_own',
            'budget.expense.log',
            'budget.expense.view',
            'budget.expense.approve',
            'budget.report.view',

            /** Performance */
            'performance.evaluate',
            'performance.rate',
            'performance.remark',
            'performance.view_all',
            'performance.view_own',
            'performance.kpi.manage',
            'performance.average.compute',

            /** Reports & Documents */
            'report.auto_generate',
            'report.upload',
            'report.download',
            'report.review',
            'report.delete',
            'document.upload',
            'document.view',
            'document.delete',

            /** Notifications */
            'notification.send',
            'notification.receive',
            'notification.deadline.alert',
            'notification.overdue.alert',

            /** System */
            'system.dashboard.view',
            'system.settings.manage',
            'department.manage',
            'financial_year.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        /** ROLES @var  $admin */
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $hod   = Role::firstOrCreate(['name' => 'Head of Department']);
        $staff = Role::firstOrCreate(['name' => 'Staff']);

        /** Admin → EVERYTHING */
        $admin->syncPermissions(Permission::all());

        /** Head of Department  */
        $hod->syncPermissions([
            'user.view',
            'user.create',
            'user.update',
            'user.deactivate',

            'task.view_all',
            'task.create',
            'task.assign',
            'task.reassign',
            'task.rollback',
            'task.approve',
            'task.reject',
            'task.comment',
            'task.close',

            'budget.view_all',
            'budget.allocate',
            'budget.assign_to_staff',
            'budget.expense.view',
            'budget.expense.approve',
            'budget.report.view',

            'performance.evaluate',
            'performance.rate',
            'performance.remark',
            'performance.view_all',
            'performance.average.compute',

            'report.auto_generate',
            'report.download',
            'report.review',

            'notification.receive',
            'notification.deadline.alert',
            'notification.overdue.alert',

            'system.dashboard.view',
        ]);

        /** Staff */
        $staff->syncPermissions([
            'task.view_assigned',
            'task.progress.update',
            'task.progress.submit',
            'task.emerging_activity.add',
            'task.timelog.add',

            'budget.view_own',
            'budget.submit',
            'budget.expense.log',

            'report.upload',
            'document.upload',
            'document.view',

            'performance.view_own',

            'notification.receive',

            'system.dashboard.view',
        ]);

        $this->enableForeignKeys('roles');
        $this->enableForeignKeys('permissions');
    }
}
