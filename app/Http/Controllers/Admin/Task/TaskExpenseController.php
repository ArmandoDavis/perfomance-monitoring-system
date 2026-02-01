<?php

namespace App\Http\Controllers\Admin\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\ExpenseRequest;
use App\Models\Expense;
use App\Models\Task\Task;
use App\Repositories\Admin\Task\TaskExpenseRepository;

class TaskExpenseController extends Controller
{
    protected $expenseRepo;

    public function __construct()
    {
        $this->expenseRepo = new TaskExpenseRepository();
    }

    public function store(ExpenseRequest $request, Task $task)
    {
        $this->expenseRepo->store($task, $request->all(), $request->file('receipt'));
        return redirect()->back()->with('flash_success', __('Expense added successfully'));
    }

    public function approve(Expense $expense)
    {
        if ($expense->approved_at) {
            return redirect()->redirect()->back()->with('flash_warning', __('Expense already approved.'));
        }

        $this->expenseRepo->approve($expense);
        return redirect()->redirect()->back()->with('flash_success', __('Expense approved successfully.'));
    }
}
