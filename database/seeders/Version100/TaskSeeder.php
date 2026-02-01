<?php

use App\Models\Access\User;
use App\Models\Department;
use App\Models\System\CodeValue;
use App\Models\Task\Task;
use Carbon\Carbon;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('tasks');
        $faker = Factory::create();

        $hod = User::role('Head of Department')->first();
        $department = Department::where('name', 'Human Resources')->first();
        $statuses = CodeValue::whereIn('reference', ['SCS001', 'SCS002', 'SCS003', 'SCS004', 'SCS005', 'SCS006', 'SCS007'])->get();

        $completedRefs = ['SCS005', 'SCS006']; // Done, Deployed
        $ongoingRefs = ['SCS003', 'SCS004'];   // In Progress, Submitted
        $backlogRefs = ['SCS001', 'SCS002'];   // Backlog, Todo

        $years = [2024, 2025, 2026];

        foreach ($years as $year) {
            for ($i = 0; $i < 15; $i++) {

                $month = rand(1, 12);
                $createdAt = Carbon::create($year, $month, rand(1, 28), rand(8, 16), 0, 0);

                if ($year < now()->year) {
                    $ref = $faker->randomElement([...$completedRefs, 'SCS007']);
                    $progress = ($ref == 'SCS007') ? rand(10, 50) : 100;

                } elseif ($year == now()->year) {
                    $ref = $faker->randomElement([
                        'SCS001', 'SCS002', 'SCS003', 'SCS004', 'SCS005'
                    ]);

                    $progress = match($ref) {
                        'SCS005' => 100,
                        'SCS004' => 90,
                        'SCS003' => rand(30, 80),
                        default => rand(0, 20)
                    };

                } else {
                    $ref = $faker->randomElement($backlogRefs);
                    $progress = rand(0, 10);
                }

                $status = $statuses->firstWhere('reference', $ref);
                $budget = $faker->randomElement([2000000, 3500000, 5000000, 7500000, 10000000]);
                //dump("Year: $year | Ref: $ref | Status ID: " . $status->id);

                DB::table('tasks')->insert([
                    'title'            => $faker->catchPhrase . " ($year)",
                    'uuid'             => str_unique(),
                    'department_id'    => $department->id,
                    'description'      => $faker->paragraph,
                    'created_by'       => $hod->id,
                    'allocated_budget' => $budget,
                    'spent_amount'     => 0,
                    'remaining_budget' => $budget,
                    'status_cv_id'     => $status->id,
                    'start_date'       => $createdAt,
                    'end_date'         => $createdAt->copy()->addMonths(rand(1, 4)),
                    'progress_percent' => $progress,
                    'is_active'        => true,
                    'created_at'       => $createdAt,
                    'updated_at'       => $createdAt,
                ]);
            }
        }

        $this->enableForeignKeys('tasks');
    }
}
