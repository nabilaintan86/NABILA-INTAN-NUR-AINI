<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;

class DetailRouterController extends Controller
{
    public function detail($id)
    {
        $device = Device::findOrFail($id);
        $logs = $device->activityLogs()->latest('log_time')->paginate(10);

        return view('admin.router-detail', compact('device', 'logs'));
    }
}