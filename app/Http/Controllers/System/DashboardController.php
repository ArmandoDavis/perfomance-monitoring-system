<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Access\User;
use App\Models\Expense;
use App\Models\System\CodeValue;
use App\Models\Task\PerformanceScore;
use App\Models\Task\Task;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    use AuthorizesRequests;

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
}
