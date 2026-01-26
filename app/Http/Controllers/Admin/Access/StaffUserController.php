<?php
namespace App\Http\Controllers\Admin\Access;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserRequest;
use App\Models\Access\User;
use App\Repositories\Access\RoleRepository;
use App\Repositories\Access\UserRepository;
use Illuminate\Http\Request;
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
        $roles = $this->roleRepo->getActiveRoles();
        return view('pages.admin.user.staff.create', compact('roles'));
    }

    public function edit(User $user)
    {
        $roles = $this->roleRepo->getActiveRoles();
        return view('pages.admin.user.staff.edit', compact('user', 'roles'));
    }

    public function store(UserRequest $request)
    {
        $user = $this->userRepo->store($request->all());
        return redirect()->route('admin.users.staff.profile', $user->uid)->with('success', __('New staff created successfully'));
    }

    public function update(UserRequest $request, User $user)
    {
        $this->userRepo->update($user, $request->all());
        return  redirect()->route('admin.users.staff.profile', $user->uid)->with('success', __('Staff details updated successfully'));
    }

    public function profile(User $user)
    {
        return view('pages.admin.user.staff.profile.profile', compact('user'));
    }

    public function delete(Request $request, User $user)
    {
        if ($user->id === user_id()) {
            return redirect()->back()->with('error', __('You can delete your own account'));
        }

        $this->userRepo->delete($user);
        return  redirect()->route('admin_panel.users.index')->with('success', __('Staff user deleted successfully'));
    }

    public function resendPassowrd(Request $request)
    {
        $this->userRepo->resendPassword($request->all());
        return redirect()->back()->with('success', __('messages.new_password_sent'));
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
                'message' => __('messages.cannot_disable_self')
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
        $activities = Activity::where('causer_id', $user->id)
            ->with(['subject'])
            ->select('activity_log.*');

        return DataTables::of($activities)
            ->addColumn('description', function ($activity) {
                $badgeClass = [
                    'created' => 'success',
                    'updated' => 'info',
                    'deleted' => 'danger'
                ][$activity->event] ?? 'secondary';

                return sprintf(
                    '<span class="badge badge-%s">%s</span> %s',
                    $badgeClass,
                    ucfirst($activity->event),
                    $activity->description
                );
            })
            ->addColumn('subject', function ($activity) {
                return $activity->subject
                    ? class_basename($activity->subject) . ' #' . $activity->subject->id
                    : '<span class="text-muted">N/A</span>';
            })
            ->addColumn('changes', function ($activity) {
                if ($activity->properties && count($activity->properties['attributes'] ?? [])) {
                    return sprintf(
                        '<button class="btn btn-sm btn-outline-primary view-changes" data-properties="%s">%s</button>',
                        htmlspecialchars(json_encode($activity->properties), ENT_QUOTES, 'UTF-8'),
                        __('label.view_changes')
                    );
                }
                return '<span class="text-muted">'.__('label.no_changes').'</span>';
            })
            ->addColumn('date', function ($activity) {
                return sprintf(
                    '%s<div class="text-muted small">%s</div>',
                    $activity->created_at->format('M d, Y h:i A'),
                    $activity->created_at->diffForHumans()
                );
            })
            ->rawColumns(['description', 'subject', 'changes', 'date'])->make(true);
    }


    public function getAllForDt()
    {
        return DataTables::of($this->userRepo->getAllForDt())
            ->addColumn('created_at', function($user) {
                return $user->created_at->diffForHumans();
            })
            ->addColumn('admin_badge', function($user) {
                return getBooleanBadge($user->is_super_admin);
            })
            ->addColumn('status_badge', function($user) {
                return getStatusBadge($user->is_active);
            })->rawColumns(['admin_badge', 'status_badge'])->make(true);
    }
}
