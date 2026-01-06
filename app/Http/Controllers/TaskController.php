<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\TaskBudget;

class TaskController extends Controller
{
    //
    public function index()
    {
        // $tasks = Task::with('department')->latest()->get();
         // return view('admin.taskview', compact('tasks')); // ✅ FIXED
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
        $tasks = Task::with(['department', 'budgets'])->latest()->get();
         return view('admin.taskview', compact('tasks')); // ✅ FIXED
    }

    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }
         $departments = Department::all();
         return view('admin.createtask', compact('departments'));
    }

    public function store(Request $request)
    {
        Task::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'department_id' => $request->department_id,
            'start_date'    => $request->start_date,
            'due_date'      => $request->due_date,
            'status'        => 'not_started',
            'created_by'    => Auth::id(),
        ]);

        return redirect()->route('admin.tasks.index') // ✅ FIXED
            ->with('success', 'Task created successfully');
    }
    public function createBudget(Task $task)
{
    return view('admin.createbudget', compact('task'));
}

public function storeBudget(Request $request, Task $task)
    {
    $request->validate([
        'allocated_amount' => 'required|numeric|min:0',
        'description'      => 'nullable|string|max:255',
    ]);

    TaskBudget::create([
        'task_id'          => $task->id,
        'allocated_amount' => $request->allocated_amount,
        'description'      => $request->description,
        'created_by'       => Auth::id(),
    ]);
    return redirect()->route('admin.tasks.index')
        ->with('success', 'Budget allocated successfully');
    } 
    // Show form to edit budget
    public function editBudget(Task $task, TaskBudget $budget)
    {
    return view('admin.editbudget', compact('task', 'budget'));
    }

// Update budget
    public function updateBudget(Request $request, Task $task, TaskBudget $budget)
    {
    $request->validate([
        'allocated_amount' => 'required|numeric|min:0',
    ]);

    $budget->update([
        'allocated_amount' => $request->allocated_amount,
    ]);

    return redirect()->route('admin.tasks.index')
        ->with('success', 'Budget updated successfully');
    }

// Delete budget
    public function destroyBudget(Task $task, TaskBudget $budget)
    {
    $budget->delete();

    return redirect()->route('admin.tasks.index')
        ->with('success', 'Budget deleted successfully');
    }
//     //SUBTASKS CREATION
//     // Show all sub-tasks for a major task
//     public function subTasks(Task $task)
//     {
//         $subTasks = $task->budgets; // already defined relation in Task model
//         return view('admin.subtasks', compact('task', 'subTasks'));
//     }
    
// // Show form to create sub-task
//     public function createSubTask(Task $task)
//     {
//     return view('admin.createsubtask', compact('task'));
//     }

// // Save sub-task
//     public function storeSubTask(Request $request, Task $task)
//     {
//     $request->validate([
//         'title' => 'required|string',
//         'allocated_amount' => 'required|numeric|min:0',
//         'description' => 'nullable|string',
//     ]);

//     $task->budgets()->create([
//         'title' => $request->title,
//         'allocated_amount' => $request->allocated_amount,
//         'description' => $request->description,
//         'created_by' => Auth::id(),
//     ]);

//     return redirect()->route('admin.tasks.subtasks', $task->id)
//         ->with('success', 'Sub-task added successfully');
//     }
//     // Show edit form for a sub-task
// public function editSubTask($taskId, $subTaskId)
// {
//     $task = Task::findOrFail($taskId);
//     $subTask = $task->budgets()->findOrFail($subTaskId);

//     return view('admin.editsubtask', compact('task', 'subTask'));
// }

// // Update the sub-task
// public function updateSubTask(Request $request, $taskId, $subTaskId)
// {
//     $subTask = TaskBudget::findOrFail($subTaskId);
//     $subTask->update([
//         'title' => $request->title,
//         'allocated_amount' => $request->allocated_amount,
//     ]);

//     return redirect()->route('admin.tasks.subtasks', $taskId)
//         ->with('success', 'Sub-task updated successfully.');
// }

// // Delete a sub-task
// public function destroySubTask($taskId, $subTaskId)
// {
//     $subTask = TaskBudget::findOrFail($subTaskId);
//     $subTask->delete();

//     return redirect()->route('admin.tasks.subtasks', $taskId)
//         ->with('success', 'Sub-task deleted successfully.');
// }
}
