<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class KelolaRouterController extends Controller
{
    public function index()
    {
        $devices = Device::orderBy('status')
            ->orderBy('name')
            ->get();

        return view('admin.kelola-router', compact('devices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|string|max:100',
            'ip_address' => 'required|ip|unique:devices,ip_address',
        ]);

        $validated['status'] = 'up';

        Device::create($validated);

        return redirect()
            ->route('admin.kelola-router')
            ->with('success', 'Perangkat berhasil ditambahkan.');
    }

    public function update(Request $request, Device $device)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'type'       => 'required|string|max:100',
            'ip_address' => 'required|ip|unique:devices,ip_address,' . $device->id,
        ]);

        $device->update($validated);

        return redirect()
            ->route('admin.kelola-router')
            ->with('success', 'Data perangkat berhasil diperbarui.');
    }

    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()
            ->route('admin.kelola-router')
            ->with('success', 'Perangkat berhasil dihapus.');
    }
}
