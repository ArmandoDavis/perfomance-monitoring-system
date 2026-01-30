<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Database\DisableForeignKeys;


/**
 * Class AccessTableSeeder.
 */
class Version100TableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        $this->call(\CodesTableSeeder::class);
        $this->call(\CodeValuesTableSeeder::class);
        $this->call(\CurrencySeeder::class);
        $this->call(\LabelSeeder::class);

        //$this->call(\RolesAndPermissionsSeeder::class);
        $this->call(\TaskPermissionSeeder::class);
        $this->call(\UserManagementPermissionSeeder::class);
        $this->call(\ExpensePermissionSeeder::class);
        $this->call(\DepartmentPermissionSeeder::class);
        $this->call(\DepartmentSeeder::class);
        $this->call(\UserSeeder::class);
        $this->call(\TaskSeeder::class);
        $this->call(\TaskAssignmentSeeder::class);
        $this->call(\ExpenseSeeder::class);
        $this->call(\PerformanceScoreSeeder::class);

        DB::commit();
    }
}
