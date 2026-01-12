<?php

use App\Models\Department;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('departments');
        $departments = [
            ['name' => 'Human Resources', 'abbreviation' => 'HR'],
            ['name' => 'Finance', 'abbreviation' => null],
            ['name' => 'Information Communication Technology', 'abbreviation' => 'ICT'],
            ['name' => 'Procurement', 'abbreviation' => null],
            ['name' => 'Planning & Statistics', 'abbreviation' => null],
        ];

        foreach ($departments as $department) {
            Department::updateOrCreate(
                ['name' => $department['name']],
                [
                    'abbreviation' => $department['abbreviation'],
                    'is_active'  => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->enableForeignKeys('departments');
    }
}
