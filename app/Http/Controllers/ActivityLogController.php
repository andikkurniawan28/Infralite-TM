<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {
            $logs = ActivityLog::with(['user']); // eager load relasi

            return DataTables::of($logs)
                ->addIndexColumn()
                ->addColumn('user_name', function ($row) {
                    return $row->user->name ?? '-';
                })
                ->editColumn('created_at', function ($row) {
                    return $row->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['user_name']) // kalau kamu ingin render HTML nanti
                ->make(true);
        }

        return view('activity_log.index');
    }
}
