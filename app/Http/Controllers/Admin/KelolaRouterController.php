<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\DeviceType;
use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KelolaRouterController extends Controller
{
    public function index()
    {
        $devices = Device::orderBy('status')
            ->orderBy('name')
            ->get();

        return view('admin.kelola-router', [
            'devices' => $devices,
            'deviceTypes' => DeviceType::types(),
            'protocols' => DeviceType::protocols(),
        ]);
    }

    public function store(Request $request)
    {
        $this->prepareDeviceInput($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', Rule::in(DeviceType::types())],
            'protocol' => ['required', 'string', Rule::in(DeviceType::protocols())],
            'ip_address' => 'required|ip|unique:device,ip_address',
            'router_version' => 'nullable|string|max:255',
        ]);

        $validated['icon_type'] = DeviceType::iconKey($validated['type']);
        $validated['status'] = 'up';

        Device::create($validated);

        return redirect()
            ->route('admin.kelola-router')
            ->with('success', 'Perangkat berhasil ditambahkan.');
    }

    public function update(Request $request, Device $device)
    {
        $this->prepareDeviceInput($request, $device);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => ['required', 'string', Rule::in(DeviceType::types())],
            'protocol' => ['required', 'string', Rule::in(DeviceType::protocols())],
            'ip_address' => 'required|ip|unique:device,ip_address,'.$device->id,
            'router_version' => 'nullable|string|max:255',
        ]);

        $validated['icon_type'] = DeviceType::iconKey($validated['type']);
        $device->update($validated);

        return redirect()
            ->route('admin.kelola-router')
            ->with('success', 'Data perangkat berhasil diperbarui.');
    }

    protected function prepareDeviceInput(Request $request, ?Device $device = null): void
    {
        $rawType = $request->input('type');
        $rawProtocol = $request->input('protocol', $rawType);

        $request->merge([
            'type' => DeviceType::normalizeType($rawType, $device?->icon_type),
            'protocol' => DeviceType::normalizeProtocol($rawProtocol),
        ]);
    }

    public function destroy(Device $device)
    {
        $device->delete();

        return redirect()
            ->route('admin.kelola-router')
            ->with('success', 'Perangkat berhasil dihapus.');
    }
}
