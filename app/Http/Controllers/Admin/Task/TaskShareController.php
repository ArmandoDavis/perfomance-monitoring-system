<?php

namespace App\Http\Controllers\Admin\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\TaskShareRequest;
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

    public function store(TaskShareRequest $request, Task $task)
    {
        $this->shareRepository->share($task, $request->all());
        return redirect()->back()->with('flash_success', __('Task shared successfully.'));
    }

    public function transfer(TaskShareRequest $request, Task $task)
    {
        $this->shareRepository->transfer($task, $request->all());
        return redirect()->back()->with('flash_success', __('Task transferred successfully.'));
    }

    public function delete(TaskShare $share)
    {
        $task = $share->task;
        if ($task->created_by !== auth()->id() && $share->shared_by !== auth()->id() && !auth()->user()->can('task.manage')) {
            return redirect()->back()->with('flash_danger', __('You do not have permission to remove this share.'));
        }

        $this->shareRepository->removeShare($share);
        return redirect()->back()->with('flash_success', __('The employee share has been successfully removed.'));
    }

    public function modifyAccess(Request $request, TaskShare $share)
    {
        $request->validate(['target_reference' => 'required|exists:code_values,reference']);
        $this->shareRepository->modifyAccess($share, $request->target_reference);
        return redirect()->back()->with('flash_success', __('Access level updated successfully.'));
    }
}
