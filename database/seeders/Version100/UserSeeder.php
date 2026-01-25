<?php

use App\Models\Access\User;
use App\Models\Department;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('users');

        $password = 'Password';
        $hr = Department::where('name', 'Human Resources')->first();

        $admin = User::updateOrCreate(
            ['email' => 'samileking9@gmail.com'],
            [
                'name' => 'King Samile',
                'password' => $password,
                'department_id' => $hr->id,
                'uuid' => str_unique()
            ]
        );
        $admin->assignRole('Admin');

        $hod = User::updateOrCreate(
            ['email' => 'armandodavis@teganas.co.tz'],
            [
                'name' => 'Davis Makanshu',
                'password' => $password,
                'department_id' => $hr->id,
                'uuid' => str_unique()
            ]
        );
        $hod->assignRole('Head of Department');

        for ($i = 1; $i <= 5; $i++) {
            $staff = User::updateOrCreate(
                ['email' => "swaumu.davis@teganas.co.tz"],
                [
                    'name' => "Swaumu Makanshu",
                    'password' => $password,
                    'department_id' => $hr->id,
                    'uuid' => str_unique()
                ]
            );

            $staff->assignRole('Staff');
        }

        $this->enableForeignKeys('users');
    }
}
