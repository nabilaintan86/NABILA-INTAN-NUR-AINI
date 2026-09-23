<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class MapEditorController extends Controller
{
    /**
     * Halaman editor posisi device pada gambar MAP.png.
     */
    public function index()
    {
        $devices = Device::orderBy('name')->get();

        $total       = $devices->count();
        $positioned  = $devices->where('map_positioned', true)->count();

        return view('admin.map-editor', compact('devices', 'total', 'positioned'));
    }

    /**
     * Simpan koordinat satu device (posisi relatif ke gambar).
     * Body: { "device_id": 1, "pos_x": 250, "pos_y": 130 }
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|integer|exists:device,id',
            'pos_x'     => 'required|integer|min:0',
            'pos_y'     => 'required|integer|min:0',
        ]);

        Device::where('id', $validated['device_id'])->update([
            'pos_x'          => $validated['pos_x'],
            'pos_y'          => $validated['pos_y'],
            'map_positioned' => true,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Reset posisi 1 device (ada device_id) atau semua device.
     */
    public function reset(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'nullable|integer|exists:device,id',
        ]);

        $query = Device::query();

        if (!empty($validated['device_id'])) {
            $query->where('id', $validated['device_id']);
        }

        $count = $query->update([
            'pos_x'          => null,
            'pos_y'          => null,
            'map_positioned' => false,
        ]);

        return response()->json([
            'success' => true,
            'reset'   => $count,
        ]);
    }

    /**
     * JSON list device + posisi (untuk halaman /admin/map).
     */
    public function data()
    {
        $devices = Device::orderBy('name')
            ->get(['id', 'name', 'type', 'icon_type', 'pop_name', 'ip_address', 'status', 'pos_x', 'pos_y', 'map_positioned']);

        return response()->json([
            'success'    => true,
            'devices'    => $devices,
            'positioned' => $devices->where('map_positioned', true)->count(),
            'total'      => $devices->count(),
        ]);
    }
}
