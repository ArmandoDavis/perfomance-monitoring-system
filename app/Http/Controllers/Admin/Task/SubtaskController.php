<?php

namespace App\Http\Controllers\Admin\Task;

use App\Http\Controllers\Controller;
use App\Models\Subtask;
use App\Models\Task\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    // List subtasks for a task
    public function index(Task $task)
    {
        $subtasks = $task->subtasks;
        return view('admin.subtasks.index', compact('task', 'subtasks'));
    }

    // Show create form
    public function create(Task $task)
    {
        return view('admin.subtasks.create', compact('task'));
    }

    // Store subtask
    public function store(Request $request, Task $task)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'budget' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $task->subtasks()->create([
            'name'        => $request->name,
            'description' => $request->description,
            'budget'      => $request->budget,
        ]);

        return redirect()
            ->route('admin.subtasks.index', $task)
            ->with('success', 'Subtask created successfully');
    }

    /** profile section */
    public function profile($task)
    {
        $task = Task::findOrFail($task);
        logger("Entering SubtaskController::profile for Task ID: " . $task->id);
        return view('admin.subtasks.profile', compact('task'));
    }

    // Edit form
    public function edit(Subtask $subtask)
    {
        return view('admin.subtasks.edit', compact('subtask'));
    }

    // Update subtask
    public function update(Request $request, Subtask $subtask)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'budget' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $subtask->update($request->only('name', 'description', 'budget'));

        return back()->with('success', 'Subtask updated');
    }

    // Delete subtask
    public function destroy(Subtask $subtask)
    {
        $subtask->delete();
        return back()->with('success', 'Subtask deleted');
    }
}
