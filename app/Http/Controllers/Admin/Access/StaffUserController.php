<?php
namespace App\Http\Controllers\Admin\Access;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRequest;
use App\Models\Access\User;
use App\Repositories\Access\RoleRepository;
use App\Repositories\Access\UserRepository;
use App\Repositories\Admin\Department\DepartmentRepository;
use App\Repositories\System\CodeRepository;
use App\Repositories\System\CodeValueRepository;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Yajra\DataTables\DataTables;


class StaffUserController extends Controller
{
    protected $userRepo, $roleRepo, $codeValueRepo, $codeRepository, $departmentRepository;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->roleRepo = new RoleRepository();
        $this->codeValueRepo = new CodeValueRepository();
        $this->codeRepository = new CodeRepository();
        $this->departmentRepository = new DepartmentRepository();

        $this->middleware('permission:user.view')->only(['index', 'profile', 'getAllForDt']);
        $this->middleware('permission:user.create')->only(['create', 'store', 'index']);
        $this->middleware('permission:user.update')->only(['edit', 'update', 'profile', 'index']);
        $this->middleware('permission:user.delete')->only('delete', 'profile', 'index');
    }

    public function index()
    {
        return view('pages.admin.user.staff.index');
    }

    public function create()
    {
        $data['roles'] = $this->roleRepo->forSelect();
        $data['departments'] = $this->departmentRepository->getActiveDepartments();
        return view('pages.admin.user.staff.create', $data);
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);

        $data['user'] = $user;
        $codeId = $this->codeRepository->getOnlyCodeIdByNameForCodeValue("Auth User Type");
        $data['roles'] = $this->roleRepo->forSelect();
        $data['userType'] = $this->codeValueRepo->getCodeValuesForSelect($codeId);
        $data['departments'] = $this->departmentRepository->getActiveDepartments();
        return view('pages.admin.user.staff.edit', $data);
    }

    public function store(UserRequest $request)
    {
        $user = $this->userRepo->store($request->all());
        return redirect()->route('admin_panel.users.profile', $user->uuid)->with('flash_success', __('New staff created successfully'));
    }

    public function update(UserRequest $request, User $user)
    {
        $this->userRepo->update($user, $request->all());
        return  redirect()->route('admin_panel.users.profile', $user->uuid)->with('flash_success', __('Staff details updated successfully'));
    }

    public function profile(User $user)
    {
        return view('pages.admin.user.staff.profile.profile', compact('user'));
    }

    public function delete(Request $request, User $user)
    {
        if ($user->id === user_id()) {
            return redirect()->back()->with('flash_danger', __('You can not delete your own account'));
        }

        $this->userRepo->delete($user);
        return  redirect()->route('admin_panel.users.index')->with('flash_success', __('Staff user deleted successfully'));
    }

    public function updatePassowrd(UserRequest $request, User $user)
    {
        $this->userRepo->updatePassowrd($user, $request->all());
        return redirect()->back()->with('flash_success', __('Staff password has been updated successfully'));
    }

    public function toggleStatus(UserRequest $request, User $user)
    {
        $this->authorize('manageStatus', $user);

        if ($user->id === user_id() && $request->action === "deactivate") {
            return redirect()->back()->with('flash_danger', __('You can not disable your own account'));
        }
        $this->userRepo->toggleStatus($user, $request->all());
        return redirect()->back()->with('flash_success', "User status have been updated successfully");
    }

    public function causedActivity(User $user)
    {
        return view('pages.admin.user.staff.activity', compact('user'));
    }

    public function getCausedActivityForDt(User $user)
    {
        $audits = Audit::where('user_id', $user->id)->select('audits.*')->latest();
        return DataTables::of($audits)
            ->addColumn('description', function ($audit) {
                $badgeClass = [
                    'created' => 'success',
                    'updated' => 'info',
                    'deleted' => 'danger',
                ][$audit->event] ?? 'secondary';

                return sprintf(
                    '<span class="badge badge-%s">%s</span> %s',
                    $badgeClass,
                    ucfirst($audit->event),
                    class_basename($audit->auditable_type)
                );
            })

            ->addColumn('subject', function ($audit) {
                if ($audit->auditable_type && $audit->auditable_id) {
                    return class_basename($audit->auditable_type) . ' #' . $audit->auditable_id;
                }

                return '<span class="text-muted">N/A</span>';
            })

            ->addColumn('changes', function ($audit) {
                if (!empty($audit->new_values)) {
                    return sprintf(
                        '<button class="btn btn-sm btn-outline-primary view-changes"
                        data-properties="%s">%s</button>',
                        htmlspecialchars(json_encode([
                            'old' => $audit->old_values,
                            'new' => $audit->new_values,
                        ]), ENT_QUOTES, 'UTF-8'),
                        __('View changes')
                    );
                }

                return '<span class="text-muted">' . __('no changes') . '</span>';
            })

            ->addColumn('date', function ($audit) {
                return sprintf(
                    '%s<div class="text-muted small">%s</div>',
                    $audit->created_at->format('M d, Y h:i A'),
                    $audit->created_at->diffForHumans()
                );
            })

            ->rawColumns(['description', 'subject', 'changes', 'date'])
            ->make(true);
    }

    public function getAllForDt()
    {
        return DataTables::of($this->userRepo->getAllForDt())
            ->addColumn('created_at', function($user) {
                return $user->created_at->diffForHumans();
            })
            ->addColumn('user_type', function($user) {
                return $user->user_type;
            })
            ->addColumn('status_badge', function($user) {
                return getStatusBadge($user->is_active);
            })->rawColumns(['user_type', 'status_badge'])->make(true);
    }
}
