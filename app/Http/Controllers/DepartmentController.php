<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\Department\DepartmentRequest;
use App\Models\Department;
use App\Repositories\Admin\Department\DepartmentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    use AuthorizesRequests;
    protected $depertmentRepository;

    public function __construct()
    {
        $this->depertmentRepository = new DepartmentRepository();
        $this->authorizeResource(Department::class, 'department');
        $this->middleware('permission:department.manage');
    }

    public function index() {
        return view('pages.admin.department.index');
    }

    public function create() {
        return view('pages.admin.department.create');
    }

    public function store(DepartmentRequest $request)
    {
        $this->depertmentRepository->store($request->all());
        return redirect()->back()->with('success', "Department Created Successfully");
    }

    public function edit(Department $department) {
        //$this->authorize('update', $department);
        return view('pages.admin.department.edit', compact('department'));
    }

    public function update(DepartmentRequest $request, Department $department)
    {
        try {
            $this->depertmentRepository->update($department, $request->all());
            return redirect()->back()->with('success', "Department Updated Successfully");
        } catch (\Exception $exception) {
            Log::error("Fail to update department: " . $exception->getMessage());
            return redirect()->back()->with('error', "Fail to update department");
        }
    }

    public function profile(Department $department)
    {
        return view('pages.admin.department.profile.profile', compact('department'));
    }

    public function delete(Request $request, Department $department)
    {
        $this->depertmentRepository->delete($department);
        return redirect()->route('admin_panel.department.index')->with('success', 'Department Deleted Successfully');
    }

    public function changeDepartmentStatus(DepartmentRequest $request, Department $department)
    {
       $message = $this->depertmentRepository->changeDepartmentStatus($department, $request->all());
       return redirect()->back()->with('success', $message);
    }

    public function getActiveDepartments()
    {
        return $this->depertmentRepository->getActiveDepartments();
    }


    public function getAllForDt(Request $request)
    {
        return DataTables::of($this->depertmentRepository->getAllForDt())
            ->addColumn('created_at', function($user) {
                return $user->created_at->diffForHumans();
            })
            ->addColumn('status_badge', function($department) {
                return getStatusBadge($department->is_active);
            })
            ->addColumn('actions', function ($department) {
                return view('pages.admin.department.partials.actions', compact('department'))->render();
            })->rawColumns(['status_badge', 'actions'])->make(true);
    }
}
