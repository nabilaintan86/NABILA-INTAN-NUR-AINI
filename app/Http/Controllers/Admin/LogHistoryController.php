<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Device;
use Illuminate\Http\Request;

class LogHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('device');

        if ($request->filled('device')) {
            $query->where('device_id', $request->input('device'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('q')) {
            $keyword = trim($request->input('q'));
            $query->where(function ($builder) use ($keyword) {
                $builder->where('device_name', 'like', "%{$keyword}%")
                    ->orWhere('ip_address', 'like', "%{$keyword}%")
                    ->orWhere('reason', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('from')) {
            $query->where('log_time', '>=', $request->input('from') . ' 00:00:00');
        }

        if ($request->filled('to')) {
            $query->where('log_time', '<=', $request->input('to') . ' 23:59:59');
        }

        $logs = $query->orderBy('log_time', 'desc')->paginate(25)->withQueryString();

        return view('admin.log', [
            'logs'        => $logs,
            'statusCounts' => $this->statusCounts(),
            'devices'     => Device::orderBy('name')->get(['id', 'name', 'ip_address']),
            'prevStatus'  => $request->input('status'),
        ]);
    }

    protected function statusCounts(): array
    {
        return [
            'total'   => ActivityLog::count(),
            'up'      => ActivityLog::where('status', 'up')->count(),
            'down'    => ActivityLog::where('status', 'down')->count(),
            'warning' => ActivityLog::where('status', 'warning')->count(),
        ];
    }
}