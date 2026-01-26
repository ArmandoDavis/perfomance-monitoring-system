<?php
namespace App\Http\Controllers\Admin\Access;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRequest;
use App\Models\Access\User;
use App\Repositories\Access\RoleRepository;
use App\Repositories\Access\UserRepository;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Yajra\DataTables\DataTables;

class StaffUserController extends Controller
{
    protected $userRepo, $roleRepo;

    public function __construct()
    {
        $this->userRepo = new UserRepository();
        $this->roleRepo = new RoleRepository();
    }

    public function index()
    {
        return view('pages.admin.user.staff.index');
    }

    public function create()
    {
        $roles = $this->roleRepo->forSelect();
        return view('pages.admin.user.staff.create', compact('roles'));
    }

    public function edit(User $user)
    {
        $roles = $this->roleRepo->forSelect();
        return view('pages.admin.user.staff.edit', compact('user', 'roles'));
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
            return redirect()->back()->with('flash_danger', __('You can delete your own account'));
        }

        $this->userRepo->delete($user);
        return  redirect()->route('admin_panel.users.index')->with('flash_success', __('Staff user deleted successfully'));
    }

    public function resendPassowrd(Request $request)
    {
        $this->userRepo->resendPassword($request->all());
        return redirect()->back()->with('flash_success', __('New password resent'));
    }

    public function toggleStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'is_active' => 'required|boolean'
        ]);

        if (auth()->id() == $request->user_id && !$request->is_active) {
            return response()->json([
                'success' => false,
                'message' => __('You can not disable your own account')
            ], 403);
        }

        $user = User::findOrFail($request->user_id);
        $user->is_active = $request->is_active;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $request->is_active
                ? __('messages.user_enabled_successfully')
                : __('messages.user_disabled_successfully')
        ]);
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
