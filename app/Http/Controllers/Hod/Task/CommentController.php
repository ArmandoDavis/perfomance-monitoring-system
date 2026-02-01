<?php

namespace App\Http\Controllers\Admin\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\CommentRequest;
use App\Http\Requests\Admin\Task\TaskRequest;
use App\Models\Comment;
use App\Models\Task\Task;
use App\Repositories\Admin\Task\CommentRepository;
use App\Repositories\Admin\Task\TaskRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CommentController extends Controller
{
    use AuthorizesRequests;
    protected $commentRepository, $taskRepository;

    public function __construct()
    {
        $this->commentRepository = new CommentRepository();
        $this->taskRepository = new TaskRepository();
    }

    public function store(CommentRequest $request, Task $task)
    {
        $this->commentRepository->store($task, $request->all());
        return redirect()->back()->with('flash_success', 'Comment added successfully');
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task = $this->taskRepository->update($task, $request->all());
        return redirect()->route('admin_panel.tasks.profile', compact('task'))->with('flash_success', 'Task updated successfully');
    }

    public function delete(Comment $comment)
    {
        $this->commentRepository->delete($comment);
        return redirect()->back()->with('flash_success', 'Comment Deleted successfully');
    }
}
