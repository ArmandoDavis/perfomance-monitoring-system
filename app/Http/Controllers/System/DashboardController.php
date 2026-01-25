<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Access\User;
use App\Models\Expense;
use App\Models\System\CodeValue;
use App\Models\Task\PerformanceScore;
use App\Models\Task\Task;
use App\Repositories\Admin\Task\TaskRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use AuthorizesRequests;
    protected $taskRepository;

    public function __construct()
    {
        $this->taskRepository = new TaskRepository();
    }


    public function index()
    {
        $user = user();
        if (!$user) {
            return redirect()->route('login');
        }
        if ($user->isAdmin()) {
            return redirect()->route('admin_panel.dashboard');
        }
        return redirect()->route('frontend.dashboard.index');
    }

    public function adminDashboard()
    {
        $todo = CodeValue::getCodeValueByReference('SCS002');
        $done = CodeValue::getCodeValueByReference('SCS002');
        $deployed = CodeValue::getCodeValueByReference('SCS002');
        $submitted = CodeValue::getCodeValueByReference('SCS002');

        $todoTasks = Task::where('status_cv_id', $todo->id)->count();
        $completedTasks = Task::whereIn('status_cv_id', [$done->id, $deployed->id])->count();
        $totalExpenses = Expense::sum('amount');
        $pendingNotifications = Task::where('status_cv_id', $submitted)->count();

        $avgWeeklyScore = PerformanceScore::whereBetween(
            'created_at',
            [now()->subDays(7), now()]
        )->avg('total_score');


        // Weekly task completion trend
        $weeklyTasks = Task::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
            ->whereIn('status_cv_id', [$done->id, $deployed->id])
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $monthlyStats = DB::table('reports')
            ->selectRaw('
                MONTH(submitted_at) as month,
                COUNT(*) as reports_count
            ')->whereYear('submitted_at', now()->year)
            ->whereNull('deleted_at')->groupBy('month')
            ->orderBy('month')->get();


        // Task status distribution
        $taskStatus = Task::select('status_cv_id', DB::raw('COUNT(*) as total'))->groupBy('status_cv_id')->get();

        // Active vs inactive staff
        $staffStatus = User::role('Staff')->selectRaw('SUM(is_active = 1) as active, SUM(is_active = 0) as inactive')->first();

        // Monthly budget vs expense
        $budgetExpense = Task::selectRaw('MONTH(created_at) as month, SUM(allocated_budget) as allocated, SUM(spent_amount) as spent')->groupBy('month')->orderBy('month')->get();

         return view('dashboard.admin.dashboard', [
             'todoTasks' => $todoTasks,
             'completedTasks' => $completedTasks,
             'totalExpenses' => $totalExpenses,
             'pendingNotifications' => $pendingNotifications,
             'avgWeeklyScore' => round($avgWeeklyScore, 2),

             'weeklyTasks' => $weeklyTasks,

             'salesData' => $monthlyStats->pluck('sales'),
             'viewsData' => $monthlyStats->pluck('views'),
             'months' => $monthlyStats->pluck('month')->map(
                 fn ($m) => Carbon::create()->month($m)->format('M')
             ),

             'taskStatus' => $taskStatus,
             'staffStatus' => $staffStatus,
             'budgetExpense' => $budgetExpense,
         ]);
    }


    public function staffDashboard()
    {
        $userId = user_id();
        $year = now()->year;

        $statusMap = collect([
            'Todo' => 'SCS002',
            'In Progress' => 'SCS003',
            'Backlog' => 'SCS001',
            'Done' => 'SCS005',
            'Deployed' => 'SCS006',
            'Submitted' => 'SCS004',
        ]);

        $statusIds = Cache::remember('task_status_ids', now()->addHours(6), function () use ($statusMap) {
            return $statusMap->mapWithKeys(function ($ref, $label) {
                $cv = CodeValue::getCodeValueByReference($ref);
                return [$label => optional($cv)->id];
            })->filter();
        });

        $myTasksQuery = $this->taskRepository->getQueryUserTasks()->whereYear('tasks.updated_at', $year);

        $rawCounts = (clone $myTasksQuery)
            ->select('status_cv_id', DB::raw('COUNT(*) as total'))
            ->groupBy('status_cv_id')
            ->pluck('total', 'status_cv_id');

        $counts = $statusIds->map(function ($id) use ($rawCounts) {
            return $rawCounts[$id] ?? 0;
        });

        $totalExpenses = Expense::whereHas('task.assignments', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        })
            ->sum('amount');

        $monthlyRaw = (clone $myTasksQuery)
            ->select(
                DB::raw('MONTH(tasks.updated_at) as month'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyTasks = collect(range(1, 12))->map(function ($month) use ($monthlyRaw) {
            return [
                'month' => Carbon::create()->month($month)->format('M'),
                'total' => $monthlyRaw[$month] ?? 0
            ];
        });

        $taskStatus = $statusIds->map(function ($id, $label) use ($rawCounts) {
            return [
                'status' => $label,
                'total' => $rawCounts[$id] ?? 0
            ];
        })->values();

        $avgWeeklyScore = PerformanceScore::where('user_id', $userId)
            ->whereBetween('created_at', [now()->subDays(7), now()])
            ->avg('total_score');

        return view('dashboard.frontend.dashboard', [
            // Cards
            'todoTasks' => $counts['Todo'] ?? 0,
            'inProgressTasks' => $counts['In Progress'] ?? 0,
            'backlogTasks' => $counts['Backlog'] ?? 0,
            'completedTasks' => ($counts['Done'] ?? 0) + ($counts['Deployed'] ?? 0),
            'submittedTasks' => $counts['Submitted'] ?? 0,
            'failedTasks' => 0, // Plug in when failure status exists

            // Charts
            'monthlyTasks' => $monthlyTasks,
            'taskStatus' => $taskStatus,

            // KPIs
            'totalExpenses' => number_format($totalExpenses, 2),
            'avgWeeklyScore' => round($avgWeeklyScore, 2),
        ]);
    }
}
