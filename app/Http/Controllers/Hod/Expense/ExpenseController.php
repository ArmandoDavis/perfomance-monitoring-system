<?php

namespace App\Http\Controllers\Hod\Expense;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\ExpenseRequest;
use App\Models\Expense;
use App\Repositories\Admin\Expense\ExpenseRepository;
use App\Repositories\Admin\Task\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class ExpenseController extends Controller
{
    protected $expenseRepository, $taskRepository;

    public function __construct()
    {
        $this->expenseRepository = new ExpenseRepository();
        $this->taskRepository = new TaskRepository();
    }

    public function index()
    {
        return view('pages.hod.expenses.index');
    }

    public function create()
    {
        $tasks = $this->taskRepository->getHodTasksToBeAddedExpense();
        return view('pages.hod.expenses.create', compact('tasks'));
    }

    public function profile(Expense $expense)
    {
        return view('pages.hod.expenses.profile.profile', compact('expense'));
    }

    public function store(ExpenseRequest $request)
    {
        $task = $this->taskRepository->findById($request->task_id);
        $this->expenseRepository->store($task, $request->all(), $request->file('receipt'));
        return redirect()->back()->with('flash_success', __('Expense added successfully'));
    }

    public function getAllForDt(Request $request)
    {
        $query = $this->expenseRepository->getAllForDt();

        return DataTables::of($query)
            ->addColumn('created_at', function($row) {
                return short_date_format($row->created_at) . '<br><small class="text-muted">' . $row->created_at->diffForHumans() . '</small>';
            })
            ->addColumn('task_name', function($row) {
                return $row->task ? Str::limit($row->task->title, 40) : '<span class="text-danger">N/A</span>';
            })
            ->addColumn('amount', function($row) {
                return '<b>' . number_2_format($row->amount) . '</b>';
            })
            ->addColumn('user_name', function($row) {
                return $row->user->name ?? 'System';
            })
            ->addColumn('status', function($row) {
                return getExpenseBadge($row->approved_at);
            })
            ->rawColumns(['created_at', 'amount', 'status', 'task_name'])->make(true);
    }
}
