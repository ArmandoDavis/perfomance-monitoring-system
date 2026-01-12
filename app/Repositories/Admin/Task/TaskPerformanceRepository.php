<?php
namespace App\Repositories\Admin\Task;

use App\Models\Task\PerformanceScore;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaskPerformanceRepository extends BaseRepository
{
    const MODEL = PerformanceScore::class;

    public function store(Model $task, array $input) {
        $totalScore = ($input['timeliness_score'] + $input['quality_score'] + $input['budget_score'] + $input['kpi_score']) / 4;
        return DB::transaction(function() use($task, $input, $totalScore) {
            $this->query()->create([
                'task_id' => $task->id,
                'user_id' => $input['user_id'],
                'timeliness_score'  => $input['timeliness_score'],
                'quality_score' => $input['quality_score'],
                'budget_score' => $input['budget_score'],
                'kpi_score' => $input['kpi_score'],
                'evaluated_by' => user_id(),
                'total_score' => $totalScore,
                'remarks'  => $input['remarks'] ?? null,
            ]);
        });
    }
}
