<?php

namespace App\Http\Controllers;

use App\Repositories\Access\MyProfileRepository;
use App\Repositories\Admin\Task\TaskExpenseRepository;
use App\Repositories\Admin\Task\TaskPerformanceRepository;
use App\Repositories\Admin\Task\TaskRepository;
use App\Repositories\System\CodeValueRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected $taskRepository, $codeValueRepository, $expenseRepository, $permissionRepository;
    protected $myRepository;

    public function __construct()
    {
        $this->taskRepository = new TaskRepository();
        $this->codeValueRepository = new CodeValueRepository();
        $this->expenseRepository = new TaskExpenseRepository();
        $this->permissionRepository = new TaskPerformanceRepository();
        $this->myRepository = new MyProfileRepository();
    }

    public function index()
    {
        $user = user();

        $done = $this->codeValueRepository->getCodeValueByReference('SCS005');
        $deployed = $this->codeValueRepository->getCodeValueByReference('SCS006');
        $myTasksQuery = $this->taskRepository->getQueryUserTasks();

        $taskCount = (clone $myTasksQuery)->count();

        $completedTasks = (clone $myTasksQuery)->whereIn('status_cv_id', [$done->id, $deployed->id])->count();
        $totalExpenses = $this->expenseRepository->getTotalUserExpenses();


        // --- RECENT ACTIVITIES (LAST 10) ---
        $recentActivities = collect();

        // Recent Tasks
        $recentTasks = (clone $myTasksQuery)->latest('updated_at')->take(5)->get()
            ->map(function ($task) {
                return [
                    'type' => 'task',
                    'title' => $task->title ?? 'Task Updated',
                    'description' => 'Task status changed to ' . optional($task->status)->name,
                    'date' => $task->updated_at,
                ];
            });

        // Recent Expenses
        $recentExpenses = $this->expenseRepository->getRecentExpenses();


        // Recent Performance Scores
        $recentScores = $this->permissionRepository->recentUserPerformanceScores();
        // Merge & sort all activities
        $recentActivities = $recentActivities
            ->merge($recentTasks)
            ->merge($recentExpenses)
            ->merge($recentScores)
            ->sortByDesc('date')
            ->take(5)
            ->values();

        return view('pages.frontend.user_profile.index', [
            'user' => $user,
            'taskCount' => $taskCount,
            'completedTasks' => $completedTasks,
            'totalExpenses' => $totalExpenses,
            'recentActivities' => $recentActivities,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('flash_danger', __('Current password is incorrect'));
        }

        $this->myRepository->update_password($request->all());
        return back()->with('flash_success', __('Password updated successfully'));
    }


    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = user();
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);
        $user->update($request->only([
            'name', 'email', 'phone'
        ]));
        return redirect()->back()->with('flash_success', __('Profile updated successfully'));
    }

}
