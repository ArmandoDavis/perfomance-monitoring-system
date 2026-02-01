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
use Illuminate\Http\Request;
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
        if ($user->isHod()) {
            return redirect()->route('hod_panel.dashboard');
        }
        return redirect()->route('frontend.dashboard.index');
    }
    public function adminDashboard()
    {
        // 1. Fetch References kwa usahihi
        $statuses = CodeValue::whereIn('reference', ['SCS001', 'SCS002', 'SCS003', 'SCS004'])->get();
        $todoId = $statuses->where('reference', 'SCS001')->first()?->id;
        $doneId = $statuses->where('reference', 'SCS002')->first()?->id;
        $deployedId = $statuses->where('reference', 'SCS003')->first()?->id;
        $submittedId = $statuses->where('reference', 'SCS004')->first()?->id;

        // 2. Summary Cards
        $todoTasks = Task::where('status_cv_id', $todoId)->count();
        $completedTasks = Task::whereIn('status_cv_id', [$doneId, $deployedId])->count();
        $totalExpenses = Expense::sum('amount');
        $pendingApprovals = Task::where('status_cv_id', $submittedId)->count();

        // 3. Performance & Budget
        $avgWeeklyScore = PerformanceScore::whereBetween('created_at', [now()->subDays(7), now()])->avg('total_score') ?? 0;

        // 4. Chart Data: Monthly Budget vs Expense
        $budgetExpense = Task::selectRaw('MONTH(created_at) as month, SUM(allocated_budget) as allocated, SUM(spent_amount) as spent')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')->orderBy('month')->get();

        // 5. Chart Data: Task Distribution (Join na CodeValue kupata majina)
        $taskStatus = Task::join('code_values', 'tasks.status_cv_id', '=', 'code_values.id')
            ->select('code_values.name', DB::raw('COUNT(*) as total'))
            ->groupBy('code_values.name')->get();

        return view('dashboard.admin.dashboard', compact(
            'todoTasks', 'completedTasks', 'totalExpenses', 'pendingApprovals',
            'avgWeeklyScore', 'budgetExpense', 'taskStatus'
        ));
    }


    public function hodDashboard(Request $request)
    {
        $user = auth()->user();
        $hodDeptId = $user->department_id;

        $year = $request->get('year', now()->year);

        // Fetch Statuses once
        $statusRefs = CodeValue::whereIn('reference', ['SCS001', 'SCS005', 'SCS006', 'SCS004'])->get();
        $todoId = $statusRefs->where('reference', 'SCS001')->first()->id;
        $doneIds = $statusRefs->whereIn('reference', ['SCS005', 'SCS006'])->pluck('id');
        $submittedId = $statusRefs->where('reference', 'SCS004')->first()->id;

        // Global Year Scoping
        $taskQuery = Task::where('department_id', $hodDeptId)->whereYear('created_at', $year);

        $todoTasks = (clone $taskQuery)->where('status_cv_id', $todoId)->count();
        $completedTasks = (clone $taskQuery)->whereIn('status_cv_id', $doneIds)->count();

        $totalExpenses = Expense::whereYear('created_at', $year)
            ->whereHas('task', fn($q) => $q->where('department_id', $hodDeptId))
            ->sum('amount');

        $avgWeeklyScore = PerformanceScore::whereYear('created_at', $year)
            ->whereHas('user', fn($q) => $q->where('department_id', $hodDeptId))
            ->avg('total_score');

        // Budget vs Spent (Mapping all 12 months)
        $rawBudgetData = (clone $taskQuery)
            ->selectRaw('MONTH(created_at) as month, SUM(allocated_budget) as allocated, SUM(spent_amount) as spent')
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        // Generate full 12 months dataset
        $budgetExpense = collect(range(1, 12))->map(function ($month) use ($rawBudgetData) {
            return [
                'month' => Carbon::create()->month($month)->format('M'),
                'allocated' => $rawBudgetData->has($month) ? $rawBudgetData[$month]->allocated : 0,
                'spent' => $rawBudgetData->has($month) ? $rawBudgetData[$month]->spent : 0,
            ];
        });

        $staffStatus = User::role('Staff')
            ->where('department_id', $hodDeptId)
            ->selectRaw('COUNT(CASE WHEN is_active = 1 THEN 1 END) as active, COUNT(CASE WHEN is_active = 0 THEN 1 END) as inactive')
            ->first();

        return view('dashboard.hod.dashboard', [
            'year' => $year,
            'todoTasks' => $todoTasks,
            'completedTasks' => $completedTasks,
            'totalExpenses' => $totalExpenses,
            'avgWeeklyScore' => round($avgWeeklyScore, 2),
            'staffStatus' => $staffStatus,
            'budgetChartData' => $budgetExpense,
            'availableYears' => Task::selectRaw('YEAR(created_at) as year')->distinct()->orderBy('year', 'desc')->pluck('year')
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
