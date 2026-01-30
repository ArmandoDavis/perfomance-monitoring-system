<?php

namespace App\Http\Controllers\Admin\Task;
use App\Http\Controllers\Controller;
use App\Models\Access\User;
use App\Models\Task\Task;
use App\Models\Task\TaskShare;
use App\Repositories\Admin\Task\TaskShareRepository;
use Illuminate\Http\Request;

class TaskShareController extends Controller
{
    protected $shareRepository;

    public function __construct()
    {
        $this->shareRepository = new TaskShareRepository();
    }



    public function store(Request $request, Task $task)
    {
        $this->authorize('share', $task);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'remarks' => 'nullable|string|max:255'
        ]);

        try {
            $data = $request->all();
            $data['task_id'] = $task->id;

            $this->shareRepository->share($data);

            return redirect()->back()->with('flash_success', 'Task shared successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_danger', 'You have already shared this person.');
        }
    }

    public function delete(TaskShare $taskShare)
    {
        $this->shareRepository->removeShare($taskShare);
        $this->authorize('share', $taskShare);
        return redirect()->back()->with('flash_success', 'Access removed successfully');
    }


    /** frontend */
    public function shareTask(Request $request, Task $task)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'remarks' => 'nullable|string|max:255'
        ]);

        $sharedWith = User::findOrFail($request->user_id);

        if ($sharedWith->department_id !== auth()->user()->department_id) {
            return redirect()->back()->with('flash_danger', __('You do not have permission to share a task outside your department.'));
        }

        $exists = $task->shares()->where('shared_with', $sharedWith->id)->exists();
        if ($exists) {
            return redirect()->back()->with('flash_warning', __('This user has already been shared this task.'));
        }

        $task->shares()->create([
            'shared_with' => $sharedWith->id,
            'shared_by' => user_id(),
            'remarks' => $request->remarks,
        ]);

        return redirect()->back()->with('flash_success', __('Task has been shared successfully.'));
    }


    public function removeSharedStaff(TaskShare $share)
    {
        $task = $share->task;
        if ($task->created_by !== auth()->id() && $share->shared_by !== auth()->id() && !auth()->user()->can('task.manage')) {
            return redirect()->back()->with('flash_danger', __('You do not have permission to remove this share.'));
        }

        $this->shareRepository->removeShare($share);
        return redirect()->back()->with('flash_success', __('The employee share has been successfully removed.'));
    }
}
