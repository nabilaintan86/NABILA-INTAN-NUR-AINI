<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DeviceCsvService;
use App\Services\NetworkTopologyService;

class MonitoringController extends Controller
{
    public function __construct(private NetworkTopologyService $topologyService)
    {
    }

    public function index(DeviceCsvService $source)
    {
        // Hanya perangkat resmi (devices.csv) yang dimonitor, supaya jumlah di
        // halaman ini sama dengan Dashboard dan DHCP Lease.
        $devices = $source->officialDevices();

        $totalUp      = $devices->where('status', 'up')->count();
        $totalDown    = $devices->where('status', 'down')->count();
        $totalWarning = $devices->where('status', 'warning')->count();
        $totalUnknown = $devices->whereNotIn('status', ['up', 'down', 'warning'])->count();
        $totalServicesDown = 0; // Sementara, karena belum ada data services

        return view('admin.index', compact(
            'devices',
            'totalUp',
            'totalDown',
            'totalUnknown',
            'totalWarning',
            'totalServicesDown'
        ) + [
            'deviceSource' => $source->officialSummary(),
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