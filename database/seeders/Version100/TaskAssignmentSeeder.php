<?php

use App\Models\Access\User;
use App\Models\Task\Task;
use App\Models\Task\TaskAssignment;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class TaskAssignmentSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('task_assignments');
        $staff = User::role('Staff')->get();
        $tasks = Task::all();
        $count = $staff->count();
        $statusTodo = \App\Models\System\CodeValue::getCodeValueByReference('SCS002');

        foreach ($tasks as $task) {
            $assignedBudget = 1_000_000;
            foreach ($staff->take($count) as $user) {
                TaskAssignment::updateOrCreate(
                    [
                        'task_id' => $task->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'assigned_budget'  => $assignedBudget,
                        'remaining_budget' => $assignedBudget,
                        'spent_amount'     => 0,
                        'is_active'        => true,
                    ]
                );
            }

            $task->update(['status_cv_id' => $task->status_cv_id]);
        }

        $this->enableForeignKeys('task_assignments');
    }
}
