<?php

namespace App\Http\Controllers\Backend\Access;

use App\Http\Controllers\Controller;
use App\Repositories\Access\UserLogRepository;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserLogController extends Controller
{
    protected $userLogRepo;

    public function __construct()
    {
        $this->userLogRepo = app(UserLogRepository::class);
    }

    public function index()
    {
        return view('pages.backend.user_logs.index');
    }

    public function getAllForDt(Request $request)
    {
        return DataTables::of($this->userLogRepo->getAllForDt())
            ->addColumn('user', function ($log) {
                return optional($log->user)->name ?? $log->username;
            })
            ->addColumn('log_type', function ($log) {
                return optional($log->logType)->name ?? '-';
            })
            ->addColumn('created_at', function ($log) {
                return $log->created_at->format('Y-m-d H:i:s');
            })
            ->rawColumns(['user', 'log_type'])
            ->make(true);
    }
}
