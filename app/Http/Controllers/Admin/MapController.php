<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;

class MapController extends Controller
{
    /**
     * Halaman Network Map (gambar MAP.png + marker device).
     */
    public function index()
    {
        $devices = Device::orderBy('name')->get();

        $positionedDevices = $devices
            ->filter(fn (Device $d) => $d->pos_x !== null && $d->pos_y !== null)
            ->values();

        $unpositionedDevices = $devices
            ->filter(fn (Device $d) => $d->pos_x === null || $d->pos_y === null)
            ->values();

        return view('admin.map', compact('devices', 'positionedDevices', 'unpositionedDevices'));
    }
}
