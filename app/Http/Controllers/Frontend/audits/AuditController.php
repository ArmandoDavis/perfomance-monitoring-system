<?php

namespace App\Http\Controllers\Frontend\audits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;
use Yajra\DataTables\DataTables;

class AuditController extends Controller
{
    public function myLogs()
    {
        return view('pages.frontend.audits.my_logs');
    }

    public function getAllForDt(Request $request)
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
            ->rawColumns(['event', 'auditable_type'])->make(true);
    }
}
