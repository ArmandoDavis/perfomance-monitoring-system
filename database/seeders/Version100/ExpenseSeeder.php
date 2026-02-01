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
        $tasks = Task::with('assignments')->get();

        foreach ($tasks as $task) {
            // 2 expense per task
            $numberOfExpenses = rand(2, 4);
            $totalSpent = 0;

            for ($i = 0; $i < $numberOfExpenses; $i++) {
                $amount = rand(200000, 500000);
                $expenseDate = $task->created_at->copy()->addDays(rand(5, 20));

                Expense::create([
                    'task_id' => $task->id,
                    'user_id' => $hod->id,
                    'amount' => $amount,
                    'description' => 'Procurement of materials for ' . $task->title,
                    'receipt_path' => 'receipts/sample.pdf',
                    'approved_by' => $hod->id,
                    'approved_at' => $expenseDate,
                    'created_at' => $expenseDate,
                ]);

                $totalSpent += $amount;
            }

            $task->update([
                'spent_amount' => $totalSpent,
                'remaining_budget' => $task->allocated_budget - $totalSpent
            ]);
        }

        $this->enableForeignKeys('expenses');
    }
}
