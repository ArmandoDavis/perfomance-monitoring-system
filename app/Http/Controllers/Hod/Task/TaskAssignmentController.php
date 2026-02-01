<?php

namespace App\Http\Controllers\Admin\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\TaskAssignmentRequest;
use App\Models\Task\Task;
use App\Models\Task\TaskAssignment;
use App\Repositories\Access\UserRepository;
use App\Repositories\Admin\Task\TaskAssignmentRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskAssignmentController extends Controller
{
    use AuthorizesRequests;
    protected $taskAssignmentRepository, $userRepository;

    public function __construct()
    {
        $this->taskAssignmentRepository = new TaskAssignmentRepository();
        $this->userRepository = new UserRepository();
    }

    public function index()
    {
        return view('pages.admin.task.assignment.index');
    }

    public function store(TaskAssignmentRequest $request, Task $task)
    {
        $this->taskAssignmentRepository->store($task, $request->all());
        return redirect()->route('admin_panel.tasks.profile', compact('task'))->with('flash_success', __('Staff assigned to this task successfully'));
    }

    public function profile(Task $task)
    {
        return view('pages.admin.task.profile.profile', compact('task'));
    }

    public function update(TaskAssignmentRequest $request, TaskAssignment $assignment)
    {
        $task = $assignment->task->uuid;
        $this->taskAssignmentRepository->update($assignment, $request->all());
        return redirect()->route('admin_panel.tasks.profile', compact('task'))->with('flash_success', __('Staff assigned to this task updated successfully'));
    }

    public function delete(TaskAssignment $assignment)
    {
        $this->taskAssignmentRepository->delete($assignment);
        return redirect()->back()->with('flash_success', 'User removed from this task successfully');
    }

    public function changeTaskStatus(TaskAssignmentRequest $request, Task $task)
    {
        $message = $this->taskAssignmentRepository->changeTaskStatus($task, $request->all());
        return redirect()->back()->with('flash_success', $message);
    }
}
