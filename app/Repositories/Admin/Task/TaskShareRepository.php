<?php
namespace App\Repositories\Admin\Task;

use App\Models\Access\User;
use App\Models\Department;
use App\Models\System\CodeValue;
use App\Models\Task\Task;
use App\Models\Task\TaskProgressLog;
use App\Models\Task\TaskShare;
use App\Notifications\Admin\Task\TaskAccessNotification;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TaskShareRepository extends BaseRepository
{
    const MODEL = TaskShare::class;

    public function share(Task $task, array $data)
    {
        return DB::transaction(function () use ($task, $data) {
            $accessLevel = CodeValue::getCodeValueByReference('ACL001');
            $share = $this->query()->updateOrCreate(
                [
                    'task_id' => $task->id,
                    'shared_with_user_id' => $data['user_id'] ?? null,
                    'shared_with_department_id' => $data['department_id'] ?? null,
                ],
                [
                    'shared_by' => auth()->id(),
                    'access_level_cv_id' => $data['access_level_cv_id'] ?? $accessLevel->id,
                    'remarks' => $data['remarks'] ?? null,
                ]
            );

            $this->notifyRecipients($task, $share, 'shared');
            return $share;
        });
    }

    public function transfer(Model $task, array $data)
    {
        return DB::transaction(function () use ($task, $data) {
//            $oldDeptName = Department::query()->where('id', $task->department_id)->value('name');
            $targetDept = Department::where('id', $data['department_id'])->firstOrFail();

            $oldDeptName = $task->department;
            $task->update([
                'department_id' => $data['department_id'],
                'is_transferred' => true,
            ]);

            return TaskProgressLog::create([
                'task_id' => $task->id,
                'user_id' => auth()->id(),
                'status' => 'Transferred',
                'comment' => "Task ownership moved from " . $oldDeptName->name . " to " . $targetDept->name,
                'logged_at' => now()
            ]);
        });
    }

    public function getSharesByTask($taskId)
    {
        return $this->query()->where('task_id', $taskId)->with('sharedWithUser')->get();
    }

    public function removeShare(Model $share)
    {
        return DB::transaction(function () use ($share) {
            $this->notifyRecipients($share->task, $share, 'revoked');
            return $share->forceDelete();
        });
    }

    public function modifyAccess(TaskShare $share, string $targetReference)
    {
        return DB::transaction(function () use ($share, $targetReference) {
            $newAccessLevel = CodeValue::getCodeValueByReference($targetReference);

            $share = $share->update([
                'access_level_cv_id' => $newAccessLevel->id
            ]);

            $this->notifyRecipients($share->task, $share, 'modified');
        });
    }


    private function notifyRecipients(Task $task, TaskShare $share, string $type)
    {
        $senderName = auth()->user()->name;
        // If shared with a Department, notify all HODs of that department
        if ($share->shared_with_department_id) {
            $hods = User::role('Head of Department')->where('department_id', $share->shared_with_department_id)->get();

            foreach ($hods as $hod) {
                $hod->notify(new TaskAccessNotification($task, $type, $senderName));
            }
        }

        // If shared with a specific User
        if ($share->sharedWithUser) {
            $share->sharedWithUser->notify(new TaskAccessNotification($task, $type, $senderName));
        }
    }
}
