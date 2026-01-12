<?php

use App\Models\Access\User;
use App\Models\Task\PerformanceScore;
use App\Models\Task\Task;
use Database\DisableForeignKeys;
use Database\TruncateTable;
use Illuminate\Database\Seeder;

class PerformanceScoreSeeder extends Seeder
{
    use DisableForeignKeys, TruncateTable;

    public function run(): void
    {
        $this->disableForeignKeys('performance_scores');
        $hod = User::role('Head of Department')->first();
        $statusDone = \App\Models\System\CodeValue::getCodeValueByReference('SCS005');
        $statusSubmitted = \App\Models\System\CodeValue::getCodeValueByReference('SCS004');
        $statusDeployed = \App\Models\System\CodeValue::getCodeValueByReference('SCS006');

        $tasks = Task::whereIn('status_cv_id', [$statusDone->id, $statusSubmitted->id, $statusDeployed->id])->with('assignees')->get();

        foreach ($tasks as $task) {
            foreach ($task->assignees as $staff) {

                // Avoid duplicate evaluations
                if (PerformanceScore::where('task_id', $task->id)->where('user_id', $staff->id)->exists()) {
                    continue;
                }

                // HR-style scoring
                $timeliness = rand(60, 100);
                $quality = rand(65, 100);
                $budget = rand(70, 100);
                $kpi = rand(60, 100);

                $total =
                    ($timeliness * 0.30) +
                    ($quality * 0.30) +
                    ($budget * 0.20) +
                    ($kpi * 0.20);

                PerformanceScore::updateOrCreate(
                    [
                        'task_id' => $task->id,
                        'user_id' => $staff->id,
                    ],
                    [
                        'timeliness_score' => $timeliness,
                        'quality_score' => $quality,
                        'budget_score' => $budget,
                        'kpi_score' => $kpi,
                        'total_score' => round($total, 2),
                        'remarks' => $this->ratingRemark($total),
                        'evaluated_by' => $hod->id,
                    ]
                );
            }
        }

        $this->enableForeignKeys('performance_scores');
    }

    private function ratingRemark(float $score): string
    {
        return match (true) {
            $score >= 85 => 'Excellent performance',
            $score >= 70 => 'Very good performance',
            $score >= 50 => 'Good performance',
            default => 'Needs improvement',
        };
    }
}
