<?php

namespace App\Http\Controllers\Admin\Access;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Access\RoleRequest;
use App\Models\Access\Role;
use App\Repositories\Access\PermissionRepository;
use App\Repositories\Access\RoleRepository;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;


class RoleController extends  Controller
{
    protected $role_repo, $permission_repo;

    public function __construct() {
        $this->role_repo = new RoleRepository();
        $this->permission_repo = new PermissionRepository();
    }

    public function index()
    {
        return view('pages.admin.access.role.index');
    }

    public function create()
    {
        $permissions = $this->permission_repo->getAll()->groupBy(function($permission) {
            return strpos($permission->name, '.') !== false
                ? explode('.', $permission->name)[0]
                : 'general';
        });

        $role = null;
        return view('pages.admin.access.role.create', compact('permissions', 'role'));
    }


    public function store(RoleRequest $request)
    {
        $input = $request->all();
        $role = $this->role_repo->store($input);
        if (isset($input['permissions'])) {
            $role->permissions()->sync($input['permissions']);
        }
        return redirect()->route('admin_panel.role.profile', ['role' => $role->uuid])->with('flash_success', __('Role Created'));
    }

    public function edit(Role $role)
    {
        $permissions = $this->permission_repo->getAllGrouped();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('pages.admin.access.role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(RoleRequest $request, Role $role)
    {
        $input = $request->all();
        $role = $this->role_repo->update($input, $role);

        if (isset($input['permissions'])){
            $role->permissions()->sync($input['permissions']);
        }
        else{
            $role->permissions()->sync([]);
        }

        return redirect()->route('admin_panel.role.profile', ['role' => $role->uuid])->with('flash_success', __('Role Updated'));
    }

    public function profile(Role $role) {
        $permissions = $this->permission_repo->getAllGrouped();
        return view('pages.admin.access.role.profile.profile', compact('role', 'permissions'));
    }

    public function show(Role $role)
    {
        return $this->profile($role);
    }

    public function delete(Role $role)
    {
        if (!$role->can_be_deleted) {
            return redirect()->back()->with('flash_danger', 'This role is protected and cannot be deleted.');
        }

        $this->role_repo->delete($role);
        return redirect()->route('admin_panel.role.index')->with('flash_success', __('Role deleted'));
    }

    public function roleUser(Role $role)
    {
        $users = $role->users()->with('roles', 'permissions')->get();
        return view('pages.admin.access.role.users', [
            'role' => $role,
            'users' => $users,
        ]);
    }

    public function roleUsersPreview(Role $role)
    {
        $users = $role->users()->limit(5)->get();
        return response()->json([
            'html' => view('pages.admin.access.role.users_preview', compact('users'))->render()
        ]);
    }

    public function getAllForDt()
    {
        $result_list = $this->role_repo->getAllForDt();
        return DataTables::of($result_list)
            ->addIndexColumn()
            ->editColumn('name', function ($result_list) {
                return $result_list->name;
            })
            ->addColumn('users_count', function ($role) {
                return $role->users_count;
            })->make(true);
    }
}
