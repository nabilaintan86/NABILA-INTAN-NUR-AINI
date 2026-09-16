<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;

class MonitoringController extends Controller
{
    public function index()
    {
        $devices = Device::all();

        $totalUp      = $devices->where('status', 'up')->count();
        $totalDown    = $devices->where('status', 'down')->count();
        $totalUnknown = $devices->where('status', 'unknown')->count();
        $totalServicesDown = 0; // Sementara, karena belum ada data services

        return view('admin.index', compact(
            'devices',
            'totalUp',
            'totalDown',
            'totalUnknown',
            'totalServicesDown'
        ));
    }
}