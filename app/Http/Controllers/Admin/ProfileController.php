<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Access\MyProfileRepository;
use App\Repositories\Admin\Task\TaskExpenseRepository;
use App\Repositories\Admin\Task\TaskPerformanceRepository;
use App\Repositories\Admin\Task\TaskRepository;
use App\Repositories\System\CodeValueRepository;

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
        $myTasksQuery = $this->taskRepository->getQueryUserTasks();

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

        return view('pages.admin.user_profile.index', [
            'user' => $user,
            'recentActivities' => $recentActivities,
        ]);
    }
}
