<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Models\Department;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $department = Department::first();
        $hod = User::where('role', 'admin')->first();

        Task::create([
            'title' => 'Prepare Monthly Report',
            'description' => 'Compile the monthly HR performance report.',
            'start_date' => now(),
            'due_date' => now()->addWeek(),
            'category' => 'major',
            'priority' => 'high',
            'status' => 'not_started',
            'created_by' => $hod->id,
            'department_id' => $department->id,
        ]);
        
    }
}
