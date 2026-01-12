<?php

use App\Models\Access\User;
use App\Models\Department;
use App\Models\Task\Task;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('tasks');
        $hod = User::role('Head of Department')->first();
        $department = Department::where('name', 'Human Resources')->first();
        $statusBacklog = \App\Models\System\CodeValue::getCodeValueByReference('SCS001');

        $tasks = [
            [
                'title' => 'Recruitment Process Review',
                'description' => 'Review and improve recruitment workflow',
                'allocated_budget' => 5000000,
            ],
            [
                'title' => 'Staff Performance Appraisal',
                'description' => 'Conduct mid-year staff evaluations',
                'allocated_budget' => 3000000,
            ],
            [
                'title' => 'HR Policy Update',
                'description' => 'Update HR policies to align with new regulations',
                'allocated_budget' => 2000000,
            ],
        ];

        foreach ($tasks as $task) {
            Task::updateOrCreate(
                [
                    'title' => $task['title'],
                    'department_id' => $department->id,
                ],
                [
                    'description' => $task['description'],
                    'created_by' => $hod->id,
                    'allocated_budget' => $task['allocated_budget'],
                    'remaining_budget' => $task['allocated_budget'],
                    'status_cv_id' => $statusBacklog->id,
                    'start_date' => now(),
                    'end_date' => now()->addMonths(2),
                ]
            );
        }
        $this->enableForeignKeys('tasks');
    }
}
