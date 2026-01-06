<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;
class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $hod = User::where('role', 'admin')->first();

        Department::create([
            'name' => 'Human Resources',
            'head_id' => $hod->id,
        ]);

        Department::create([
            'name' => 'Finance',
            'head_id' => $hod->id, // can assign HoD for simplicity
        ]);
    }
}
