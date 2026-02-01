<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Yajra\DataTables\DataTables;

class AuditController extends Controller
{
    public function index()
    {
        return view('pages.admin.audits.index');
    }

    public function myLogs()
    {
        return view('pages.admin.audits.my_logs');
    }

    public function getMyLogsForDt(Request $request)
    {
        $query = Audit::where('user_id', user_id())->select('audits.*');
        return DataTables::of($query)
            ->editColumn('event', function ($row) {
                $color = match($row->event) {
                    'created' => 'success',
                    'updated' => 'warning',
                    'deleted' => 'danger',
                    default   => 'info'
                };
                return "<span class='badge bg-light-{$color} text-{$color} text-uppercase px-3'>{$row->event}</span>";
            })
            ->editColumn('auditable_type', function ($row) {
                return "<b>" . class_basename($row->auditable_type) . "</b>";
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M, Y H:i:s');
            })
            ->addColumn('actions', function ($row) {
                $old = json_encode($row->old_values);
                $new = json_encode($row->new_values);
                return '<button class="btn btn-sm btn-outline-primary radius-30" onclick=\'viewAuditDetails('.$old.', '.$new.')\'>
                    <i class="material-icons-outlined fs-6">visibility</i></button>';
            })
            ->rawColumns(['event', 'auditable_type', 'actions'])
            ->make(true);
    }


    public function getAllForDt(Request $request)
    {
        $query = Audit::with(['user:id,name,email'])->select('audits.*');

        return DataTables::of($query)
            ->editColumn('user', function ($row) {
                return $row->user ? "<b>{$row->user->name}</b><br><small>{$row->user->email}</small>" : '<span class="text-muted">System</span>';
            })
            ->editColumn('event', function ($row) {
                $color = match($row->event) {
                    'created' => 'success',
                    'updated' => 'warning',
                    'deleted' => 'danger',
                    default   => 'info'
                };
                return "<span class='badge bg-light-{$color} text-{$color} text-uppercase px-3'>{$row->event}</span>";
            })
            ->editColumn('auditable_type', function ($row) {
                $model = class_basename($row->auditable_type);
                return "<b>{$model}</b><br><small class='text-muted'>ID: {$row->auditable_id}</small>";
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d M, Y H:i:s');
            })
            ->addColumn('actions', function ($row) {
                $old = json_encode($row->old_values);
                $new = json_encode($row->new_values);

                return '<button class="btn btn-sm btn-outline-primary radius-30"
                        onclick=\'viewAuditDetails('.$old.', '.$new.')\'>
                        <i class="material-icons-outlined fs-6">visibility</i> '.__('View Changes').'
                        </button>';
            })
            ->rawColumns(['user', 'event', 'auditable_type', 'actions'])->make(true);
    }
}
