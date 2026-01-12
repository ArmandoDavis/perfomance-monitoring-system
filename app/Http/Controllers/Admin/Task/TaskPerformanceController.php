<?php

namespace App\Http\Controllers\Admin\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\TaskPerformanceRequest;
use App\Models\Task\Task;
use App\Repositories\Admin\Task\TaskPerformanceRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskPerformanceController extends Controller
{
    use AuthorizesRequests;
    protected $taskPerformanceRepository;

    public function __construct()
    {
        $this->taskPerformanceRepository = new TaskPerformanceRepository();
    }

    public function store(TaskPerformanceRequest $request, Task $task)
    {
        try {
            $this->taskPerformanceRepository->store($task, $request->all());
            return redirect()->route('admin_panel.tasks.profile', compact('task'))->with('flash_success', __('Staff performance to this task added successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('flash_danger', $e->getMessage());
        }
    }
}
