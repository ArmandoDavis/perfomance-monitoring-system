<?php

use App\Models\Access\User;
use App\Models\Expense;
use App\Models\Task\Task;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('expenses');

        $hod = User::role('Head of Department')->first();

        $tasks = Task::with('assignees')->get();

        foreach ($tasks as $task) {
            foreach ($task->assignees as $staff) {

                // Get assignment pivot
                $assignment = $staff->pivot;

                // Skip if no remaining budget
                if ($assignment->remaining_budget <= 0) {
                    continue;
                }

                $amount = min(200000, $assignment->remaining_budget);
                Expense::updateOrCreate(
                    [
                        'task_id' => $task->id,
                        'user_id' => $staff->id,
                        'amount' => $amount,
                    ],
                    [
                        'description' => 'Operational expense',
                        'receipt_path' => 'receipts/sample.pdf',
                        'approved_by' => $hod->id,
                        'approved_at' => now(),
                    ]
                );
            }
        }

        $this->enableForeignKeys('expenses');
    }
}
