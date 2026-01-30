<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\TaskRequest;
use App\Models\Access\User;
use App\Models\System\Code;
use App\Models\Task\Task;
use App\Repositories\Access\UserRepository;
use App\Repositories\Admin\Department\DepartmentRepository;
use App\Repositories\Frontend\Task\TaskRepository;
use App\Repositories\System\CodeRepository;
use App\Repositories\System\CodeValueRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class MyTaskController extends Controller
{
    protected $depertmentRepository, $taskRepository, $userRepository, $codeValueRepository, $codeRepository;

    public function __construct()
    {
        $this->depertmentRepository = new DepartmentRepository();
        $this->taskRepository = new TaskRepository();
        $this->userRepository = new UserRepository();
        $this->codeValueRepository = new CodeValueRepository();
        $this->codeRepository = new CodeRepository();
    }

    public function index()
    {
        return view('pages.frontend.task.index');
    }


    public function shared() {
        return view('pages.frontend.task.index', ['filter_type' => 'shared']);
    }

    public function nextActions() {
        return view('pages.frontend.task.index', ['filter_type' => 'next_actions']);
    }

    public function transferred() {
        return view('pages.frontend.task.index', ['filter_type' => 'transferred']);
    }

    public function profile(Task $task)
    {
        $codeId = Code::query()->where('name', 'Status')->value('id');
        $data['task'] = $task;
        $data['statuses'] = $this->codeValueRepository->getCodeValuesForSelect($codeId);
        $data['statusActions'] = $this->codeValueRepository->getCodeValuesReferenceForSelect($codeId);
        $data['myProgressLogs'] = $task->progressLogs()->where('user_id', user_id())->get();
        $data['departmentPeers'] = $this->userRepository->getActiveUsersPerDepartment();
        $data['userShare'] = $task->shares->where('shared_with', user_id())->first();
        return view('pages.frontend.task.profile.profile', $data);
    }

    public function changeTaskStatus(TaskRequest $request, Task $task)
    {
        $this->authorize('updateStatus', $task);
        $this->taskRepository->changeTaskStatus($task, $request->all());
        return redirect()->back()->with('flash_success', "Task status updated successfully");
    }

    public function undo(Task $task)
    {
         $this->taskRepository->undo($task);
        return redirect()->back()->with('flash_success', "Task undo successfully");
    }

    public function archive(Task $task)
    {
        $message = $this->taskRepository->archive($task);
        return redirect()->back()->with('flash_success', "Task archived successfully");
    }

    public function getAllForDt(Request $request)
    {
        $query = $this->taskRepository->getAllForDt();
        $filter = $request->get('filter_type');

        if ($filter == 'my_tasks') {
            $query->where('tasks.created_by', user_id());
        } elseif ($filter == 'shared') {
            $query->where('tasks.department_id', user()->department_id);
        } elseif ($filter == 'next_actions') {
            $query->where('tasks.is_active', true)->whereNull('tasks.completed_at')->where('tasks.end_date', '>=', now());
        } elseif ($filter == 'transferred') {
            $query->where('tasks.is_transferred', true);
        }

        return DataTables::of($query)
            ->addColumn('department_name', function($task) {
                return $task->department_name;
            })
            ->addColumn('created_by', function($task) {
                return $task->created_by;
            })
            ->addColumn('start_date', function($task) {
                return short_date_format_with_day($task->start_date);
            })
            ->addColumn('end_date', function($task) {
                return short_date_format_with_day($task->end_date);
            })
            ->addColumn('completed_at', function($task) {
                return short_date_format_with_day($task->completed_at);
            })
            ->addColumn('allocated_budget', function($task) {
                return number_2_format($task->allocated_budget);
            })
            ->addColumn('spent_amount', function($task) {
                return number_2_format($task->spent_amount);
            })
            ->addColumn('remaining_budget', function($task) {
                return number_2_format($task->allocated_budget);
            })
            ->addColumn('status_badge', function($task) {
                return getStatusLabelBadge($task->task_status);
            })
            ->rawColumns([
                'status_badge',
                'remaining_budget',
                'allocated_budget',
                'spent_amount',
                'completed_at',
                'start_date',
                'end_date'
            ])->make(true);
    }
}


