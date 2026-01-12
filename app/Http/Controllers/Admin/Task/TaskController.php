<?php

namespace App\Http\Controllers\Admin\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\TaskRequest;
use App\Models\System\Code;
use App\Models\Task\Task;
use App\Repositories\Access\UserRepository;
use App\Repositories\Admin\Department\DepartmentRepository;
use App\Repositories\Admin\Task\TaskRepository;
use App\Repositories\System\CodeValueRepository;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TaskController extends Controller
{
    use AuthorizesRequests;
    protected $depertmentRepository, $taskRepository, $userRepository, $codeValueRepository;

    public function __construct()
    {
        $this->depertmentRepository = new DepartmentRepository();
        $this->taskRepository = new TaskRepository();
        $this->userRepository = new UserRepository();
        $this->codeValueRepository = new CodeValueRepository();
        $this->authorizeResource(Task::class, 'task');
    }

    public function index()
    {
        //$this->authorize('viewAny', Task::class);
        return view('pages.admin.task.index');
    }

    public function create()
    {
         $departments = $this->depertmentRepository->getActiveDepartments();
         return view('pages.admin.task.create', compact('departments'));
    }

    public function store(TaskRequest $request)
    {
        $task = $this->taskRepository->store($request->all());
        return redirect()->route('admin_panel.tasks.profile', compact('task'))->with('flash_success', 'Task created successfully');
    }

    public function edit(Task $task)
    {
        $departments = $this->depertmentRepository->getActiveDepartments();
        return view('pages.admin.task.create', compact('departments', 'task'));
    }

    public function profile(Task $task)
    {
        $codeId = Code::query()->where('name', 'Status')->value('id');
        $users = $this->userRepository->getActiveStaffs();
        $statuses = $this->codeValueRepository->getCodeValuesForSelect($codeId);
        return view('pages.admin.task.profile.profile', compact('task', 'users', 'statuses'));
    }

    public function update(TaskRequest $request, Task $task)
    {
        $task = $this->taskRepository->update($task, $request->all());
        return redirect()->route('admin_panel.tasks.profile', compact('task'))->with('flash_success', 'Task updated successfully');
    }

    public function delete(Task $task)
    {
        $this->taskRepository->delete($task);
        return redirect()->route('admin_panel.tasks.index')->with('flash_success', 'Task Deleted successfully');
    }

    public function archive(Task $task)
    {
        $this->taskRepository->archive($task);
        return redirect()->route('admin_panel.tasks.index')->with('flash_success', 'Task archived successfully');
    }

    public function changeTaskStatus(TaskRequest $request, Task $task)
    {
        $message = $this->taskRepository->changeTaskStatus($task, $request->all());
        return redirect()->back()->with('flash_success', $message);
    }

    public function getAllForDt(Request $request)
    {
        return DataTables::of($this->taskRepository->getAllForDt())
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
                return getStatusBadge($task->is_active);
            })
            ->rawColumns([
                'status_badge',
                'remaining_budget',
                'allocated_budget',
                'spent_amount',
                'completed_at',
                'start_date',
                'end_date'
            ])
            ->make(true);
    }
}
