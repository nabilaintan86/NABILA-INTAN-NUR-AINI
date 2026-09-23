<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Services\NetworkTopologyService;

class MonitoringController extends Controller
{
    public function __construct(private NetworkTopologyService $topologyService)
    {
    }

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
        ) + [
            // Topology (sumber tunggal NetworkTopologyService).
            'topology' => $this->topologyService->build(),
        ]);
    }

    public function data()
    {
        return response()->json($this->topologyService->build());
    }

    /**
     * Detail satu device untuk popup pada MAP RADNET.
     * Read-only: tidak menjalankan ping / mengubah status apa pun.
     * Data (informasi, statistik, log extract) diambil dari data existing
     * lewat NetworkTopologyService::deviceDetail().
     */
    public function device(Device $device)
    {
        return response()->json(
            $this->topologyService->deviceDetail($device)
        );
    }
}