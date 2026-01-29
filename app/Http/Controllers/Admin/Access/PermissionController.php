<?php

namespace App\Http\Controllers\Admin\Access;
use App\Http\Controllers\Controller;
use App\Repositories\Access\PermissionRepository;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends  Controller
{
    protected $role_repo, $permission_repo;

    public function __construct() {
        $this->permission_repo = new PermissionRepository();
    }

    public function index()
    {
        return view('pages.admin.access.permission.index');
    }

    public function getAllForDt()
    {
        $result_list = $this->permission_repo->getAll();
        return DataTables::of($result_list)
            ->addIndexColumn()
            ->editColumn('name', function ($result_list) {
                return $result_list->name;
            })
            ->editColumn('guard_name', function ($result_list) {
                return $result_list->guard_name;
            })
            ->make(true);
    }
}
