<?php

namespace App\Http\Controllers\Admin\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Task\TaskRequest;
use App\Models\System\Code;
use App\Models\Task\Task;
use App\Repositories\Access\UserRepository;
use App\Repositories\Admin\Department\DepartmentRepository;
use App\Repositories\Admin\Task\TaskRepository;
use App\Repositories\System\CodeRepository;
use App\Repositories\System\CodeValueRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TaskController extends Controller
{
    use AuthorizesRequests;
    protected $depertmentRepository, $taskRepository, $userRepository, $codeValueRepository, $codeRepository;

    public function __construct()
    {
        $this->depertmentRepository = new DepartmentRepository();
        $this->taskRepository = new TaskRepository();
        $this->userRepository = new UserRepository();
        $this->codeValueRepository = new CodeValueRepository();
        $this->codeRepository = new CodeRepository();

        $this->middleware('permission:task.view')->only(['index', 'myTasks', 'shared', 'nextActions', 'transferred', 'profile', 'getAllForDt']);
        $this->middleware('permission:task.create')->only(['create', 'store']);
        $this->middleware('permission:task.update')->only(['edit', 'update', 'changeTaskStatus']);
        $this->middleware('permission:task.delete')->only(['delete', 'archive']);
    }

    public function index()
    {
        return view('pages.admin.task.index');
    }

    public function myTasks() {
        return view('pages.admin.task.index', ['filter_type' => 'my_tasks']);
    }

    public function shared() {
        return view('pages.admin.task.index', ['filter_type' => 'shared']);
    }

    public function nextActions() {
        return view('pages.admin.task.index', ['filter_type' => 'next_actions']);
    }

    public function transferred() {
        return view('pages.admin.task.index', ['filter_type' => 'transferred']);
    }

    public function create()
    {
         $codeId = $this->codeRepository->codeByName("Status")->id;
         $data['departments'] = $this->depertmentRepository->getActiveDepartments();
        $data['statuses'] = $this->codeValueRepository->getCodeValuesForSelect($codeId);
        $data['users'] = $this->userRepository->getActiveStaffs();
         return view('pages.admin.task.create', $data);
    }

    public function store(TaskRequest $request)
    {
        $task = $this->taskRepository->store($request->all());
        return redirect()->route('admin_panel.tasks.profile', compact('task'))->with('flash_success', 'Task created successfully');
    }

    public function edit(Task $task)
    {
        $this->authorize('update', $task);
        $codeId = $this->codeRepository->codeByName("Status")->id;
        $data['task'] = $task;
        $data['departments'] = $this->depertmentRepository->getActiveDepartments();
        $data['statuses'] = $this->codeValueRepository->getCodeValuesForSelect($codeId);
        $data['users'] = $this->userRepository->getActiveStaffs();
        return view('pages.admin.task.edit', $data);
    }

    public function profile(Task $task)
    {
        $this->authorize('view', $task);
        $codeId = Code::query()->where('name', 'Status')->value('id');
        $codeAccessId = $this->codeRepository->getOnlyCodeIdByNameForCodeValue('Access Level');

        $data['task'] = $task;
        $data['users'] = $this->userRepository->getActiveStaffs();
        $data['userAssigned'] = $this->userRepository->getNonEvaluatedUserForThisTask($task->id);
        $data['statuses'] = $this->codeValueRepository->getCodeValuesForSelect($codeId);
        $data['hoursSinceCompletion'] = Carbon::parse($task->completed_at)->diffInHours(now());
        $data['departments'] = $this->depertmentRepository->getDepartmentToShareTask($task);
        $data['access'] = $this->codeValueRepository->getCodeValuesForSelect($codeAccessId);
        $data['userShare'] = $task->shares->where('shared_with', user_id())->first();
        return view('pages.admin.task.profile.profile', $data);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);
        $this->taskRepository->update($task, $request->all());
        return redirect()->back()->with('flash_success', 'Task updated successfully');
    }

    public function delete(Task $task)
    {
        $this->authorize('delete', $task);
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
        $this->authorize('updateStatus', $task);
        $this->taskRepository->changeTaskStatus($task, $request->all());
        return redirect()->back()->with('flash_success', "Task status has been updated successfully");
    }

    public function getAllForDt(Request $request)
    {
        $query = $this->taskRepository->getAllForDt();
        $filter = $request->get('filter_type');

        if ($filter == 'my_tasks') {
            $query->where('tasks.created_by', user_id());
        } elseif ($filter == 'shared') {
            $query->where('tasks.department_id', auth()->user()->department_id);
        } elseif ($filter == 'next_actions') {
            $query->where('tasks.is_active', true)->whereNull('tasks.completed_at')->where('tasks.end_date', '>=', now());
        } elseif ($filter == 'transferred') {
            $query->where('tasks.is_transferred', true);
        }

        return DataTables::of($query)
            ->addColumn('department_name', fn($task) => $task->department_name)
            ->addColumn('created_by', fn($task) => $task->creator_name ?? $task->created_by)
            ->addColumn('start_date', fn($task) => short_date_format_with_day($task->start_date))
            ->addColumn('end_date', fn($task) => short_date_format_with_day($task->end_date))
            ->addColumn('completed_at', fn($task) => short_date_format_with_day($task->completed_at))
            ->addColumn('allocated_budget', fn($task) => number_2_format($task->allocated_budget))
            ->addColumn('spent_amount', fn($task) => number_2_format($task->spent_amount))
            ->addColumn('remaining_budget', function ($task) {
                return number_2_format($task->allocated_budget - $task->spent_amount);
            })
            ->addColumn('status_badge', fn($task) => getStatusBadge($task->is_active))
            ->rawColumns(['status_badge'])
            ->make(true);
    }
}
