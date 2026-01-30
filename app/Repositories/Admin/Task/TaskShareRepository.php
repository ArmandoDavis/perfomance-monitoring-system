<?php
namespace App\Repositories\Admin\Task;

use App\Models\Department;
use App\Models\Task\Task;
use App\Models\Task\TaskShare;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaskShareRepository extends BaseRepository
{
    const MODEL = TaskShare::class;

    public function share(array $data)
    {
        return DB::transaction(function () use($data) {
            return $this->query()->create([
                'task_id' => $data['task_id'],
                'shared_with' => $data['user_id'],
                'shared_by' => auth()->id(),
                'remarks' => $data['remarks'] ?? null,
            ]);
        });
    }


    public function transferTask(Task $task, array $input)
    {
        return DB::transaction(function () use ($task, $input) {
            $oldDept = $task->department->name;
            $newDept = Department::findOrFail($input['department_id'])->name;

            $task->update([
                'department_id' => $input['department_id'],
                'is_transferred' => true,
            ]);
            return $task;
        });
    }

    public function getSharesByTask($taskId)
    {
        return $this->query()->where('task_id', $taskId)->with('sharedWithUser')->get();
    }

    public function removeShare(Model $share)
    {
        return DB::transaction(function () use ($share) {
            return $share->forceDelete();
        });
    }

}
