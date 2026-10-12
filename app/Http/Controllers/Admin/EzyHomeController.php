<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EzyHomeArea;
use App\Models\EzyHomeConnection;
use App\Models\EzyHomeDevice;
use App\Models\EzyHomePosition;
use App\Services\EzyHomeTopologyService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EzyHomeController extends Controller
{
    public function __construct(
        private EzyHomeTopologyService $topology,
    ) {}

    private function guardSuperAdmin(string $message): ?Response
    {
        if (auth()->check() && auth()->user()->role === 'super_admin') {
            return null;
        }

        return response()->json([
            'success' => false,
            'message' => $message,
        ], 403);
    }

    /**
     * Halaman utama EzyHome.
     */
    public function index()
    {
        $devices = EzyHomeDevice::orderBy('name')->get();
        $areas   = EzyHomeArea::where('is_active', true)->orderBy('sort_order')->get();

        return view('admin.ezyhome.index', [
            'devices'      => $devices,
            'networkAreas' => $areas,
            'topology'     => $this->topology->build(),
            'deviceList'   => $this->topology->deviceList(),
            'deviceTypes'  => ['Router', 'Switch', 'OLT', 'EPON', 'Server', 'Access Point', 'Other'],
            'protocols'    => ['ICMP', 'HTTP', 'SNMP', 'TCP', 'SSH'],
            'isSuperAdmin' => auth()->check() && auth()->user()->role === 'super_admin',
        ]);
    }

    /**
     * Endpoint JSON data topology (auto-refresh).
     */
    public function data()
    {
        return response()->json($this->topology->build());
    }

    /**
     * Endpoint JSON daftar device untuk panel.
     */
    public function devices()
    {
        return response()->json([
            'success' => true,
            'devices' => $this->topology->deviceList(),
        ]);
    }

    /**
     * Detail satu device.
     */
    public function device(EzyHomeDevice $device)
    {
        return response()->json($this->topology->deviceDetail($device));
    }

    /**
     * Tambah device EzyHome baru.
     */
    public function storeDevice(Request $request)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menambah perangkat.')) {
            return $forbidden;
        }
        $validated = $request->validate([
            'name'           => 'required|string|max:255|unique:ezyhome_devices,name',
            'type'           => 'nullable|string|max:64',
            'protocol'       => 'nullable|string|max:32',
            'ip_address'     => 'nullable|string|max:64',
            'router_version' => 'nullable|string|max:255',
            'network_area_id' => 'nullable|integer|exists:ezyhome_areas,id',
        ]);

        $device = EzyHomeDevice::create([
            'name'           => $validated['name'],
            'type'           => $validated['type'] ?? 'Other',
            'protocol'       => $validated['protocol'] ?? 'ICMP',
            'ip_address'     => $validated['ip_address'] ?? null,
            'router_version' => $validated['router_version'] ?? null,
            'pop_name'       => 'EzyHome',
            'status'         => 'unknown',
            'source'         => 'db',
        ]);

        // Kalau ada area_id → langsung bikin posisi
        if (! empty($validated['network_area_id'])) {
            $area = EzyHomeArea::find($validated['network_area_id']);
            if ($area) {
                EzyHomePosition::create([
                    'ezyhome_device_id' => $device->id,
                    'ezyhome_area_id'   => $area->id,
                    'pos_x'             => $area->pos_x + 40,
                    'pos_y'             => $area->pos_y + 60,
                ]);
            }
        }

        return response()->json([
            'success'  => true,
            'message'  => "Perangkat {$device->name} berhasil ditambahkan.",
            'device'   => $device,
            'devices'  => $this->topology->deviceList(),
            'topology' => $this->topology->build(),
        ], 201);
    }

    /**
     * Update device EzyHome.
     */
    public function updateDevice(Request $request, EzyHomeDevice $device)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengubah perangkat.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'nullable|string|max:64',
            'protocol'       => 'nullable|string|max:32',
            'ip_address'     => 'nullable|string|max:64',
            'router_version' => 'nullable|string|max:255',
        ]);

        $device->update($validated);

        return response()->json([
            'success'  => true,
            'message'  => "Perangkat {$device->name} berhasil diperbarui.",
            'device'   => $device->fresh(),
            'devices'  => $this->topology->deviceList(),
            'topology' => $this->topology->build(),
        ]);
    }

    /**
     * Hapus device EzyHome (full).
     */
    public function destroyDevice(EzyHomeDevice $device)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menghapus perangkat.')) {
            return $forbidden;
        }

        $name = $device->name;
        $device->delete();

        return response()->json([
            'success'  => true,
            'message'  => "Perangkat {$name} berhasil dihapus.",
            'devices'  => $this->topology->deviceList(),
            'topology' => $this->topology->build(),
        ]);
    }

    /**
     * Hapus satu posisi EzyHome (device tetap ada).
     */
    public function destroyPosition(EzyHomePosition $position)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menghapus posisi perangkat.')) {
            return $forbidden;
        }

        $device = $position->device;
        $deviceName = $device ? $device->name : ('#' . $position->ezyhome_device_id);

        EzyHomeConnection::where('from_position_id', $position->id)
            ->orWhere('to_position_id', $position->id)
            ->delete();

        $position->delete();

        return response()->json([
            'success'  => true,
            'message'  => "Posisi perangkat {$deviceName} berhasil dihapus dari POP ini.",
            'devices'  => $this->topology->deviceList(),
            'topology' => $this->topology->build(),
        ]);
    }

    /**
     * Simpan posisi device (drag & drop).
     */
    public function updatePosition(Request $request, EzyHomeDevice $device)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengatur posisi.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'pos_x'           => 'required|numeric',
            'pos_y'           => 'required|numeric',
            'ezyhome_area_id' => 'nullable|integer|exists:ezyhome_areas,id',
            'position_id'     => 'nullable|integer|exists:ezyhome_positions,id',
        ]);

        $positionId = $validated['position_id'] ?? null;
        $areaId = $validated['ezyhome_area_id'] ?? null;
        $positionId = $validated['position_id'] ?? null;
        $areaId = $validated['ezyhome_area_id'] ?? null;

        // EzyHome = multi-position (mirror Monitoring).
        // - position_id ada → update posisi existing (drag node di canvas)
        // - position_id kosong → create posisi baru (drag device dari panel)
        if ($positionId) {
            $pos = EzyHomePosition::find($positionId);
            if ($pos) {
                $pos->update([
                    'pos_x'           => (int) round($validated['pos_x']),
                    'pos_y'           => (int) round($validated['pos_y']),
                    'ezyhome_area_id' => $areaId,
                ]);
            }
        } else {
            EzyHomePosition::create([
                'ezyhome_device_id' => $device->id,
                'ezyhome_area_id'   => $areaId,
                'pos_x'             => (int) round($validated['pos_x']),
                'pos_y'             => (int) round($validated['pos_y']),
            ]);
        }

        return response()->json([
            'success'  => true,
            'message'  => "Posisi perangkat {$device->name} berhasil diperbarui.",
            'devices'  => $this->topology->deviceList(),
            'topology' => $this->topology->build(),
        ]);
    }

    /**
     * Bikin relasi antar-device EzyHome.
     */
    public function storeConnection(Request $request)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menambah relasi.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'from_device_id'   => 'required|exists:ezyhome_devices,id',
            'to_device_id'     => 'required|exists:ezyhome_devices,id|different:from_device_id',
            'from_position_id' => 'nullable|integer|exists:ezyhome_positions,id',
            'to_position_id'   => 'nullable|integer|exists:ezyhome_positions,id',
            'connection_type'  => 'nullable|string|max:50',
            'direction'        => 'required|string|in:forward,reverse,bidirectional,none',
        ]);

        $conn = EzyHomeConnection::updateOrCreate(
            [
                'from_position_id' => $validated['from_position_id'] ?? null,
                'to_position_id'   => $validated['to_position_id'] ?? null,
                'from_device_id'   => $validated['from_device_id'],
                'to_device_id'     => $validated['to_device_id'],
            ],
            [
                'connection_type' => $validated['connection_type'] ?? 'ethernet',
                'direction'       => $validated['direction'],
            ]
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Relasi berhasil ditambahkan.',
            'connection' => $conn,
            'topology'   => $this->topology->build(),
            'devices'    => $this->topology->deviceList(),
        ]);
    }

    /**
     * Ubah arah panah relasi.
     */
    public function updateDirection(Request $request, EzyHomeConnection $connection)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengubah arah relasi.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'direction' => 'required|string|in:forward,reverse,bidirectional,none',
        ]);

        $connection->direction = $validated['direction'];
        $connection->save();

        return response()->json([
            'success'  => true,
            'message'  => 'Arah panah diperbarui.',
            'topology' => $this->topology->build(),
        ]);
    }
    /**
     * Cari posisi X kosong untuk area baru (di kanan area paling kanan).
     */
    private function nextAreaPosX(): int
    {
        $maxRight = EzyHomeArea::selectRaw('MAX(pos_x + width) as mr')->value('mr');
        return $maxRight ? ((int) $maxRight + 40) : 50;
    }

    /**
     * Posisi Y default area baru.
     */
    private function nextAreaPosY(): int
    {
        return 50;
    }

        /**
     * Tambah card area / POP EzyHome.
     */
    public function storeArea(Request $request)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menambah Card Area / POP.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:ezyhome_areas,name',
            'code'         => 'nullable|string|max:50',
            'description'  => 'nullable|string',
            'pos_x'        => 'nullable|numeric',
            'pos_y'        => 'nullable|numeric',
            'width'        => 'nullable|numeric|min:80',
            'height'       => 'nullable|numeric|min:80',
            'border_color' => 'nullable|string|max:20',
            'bg_color'     => 'nullable|string|max:20',
            'title_color'  => 'nullable|string|max:20',
            'sort_order'   => 'nullable|integer',
        ]);

        $area = EzyHomeArea::create([
            'name'         => $validated['name'],
            'code'         => $validated['code'] ?? null,
            'description'  => $validated['description'] ?? null,
            'pos_x'        => ! empty($validated['pos_x']) ? (int) $validated['pos_x'] : $this->nextAreaPosX(),
            'pos_y'        => ! empty($validated['pos_y']) ? (int) $validated['pos_y'] : $this->nextAreaPosY(),
            'width'        => (int) ($validated['width'] ?? 280),
            'height'       => (int) ($validated['height'] ?? 220),
            'border_color' => $validated['border_color'] ?? '#2563eb',
            'bg_color'     => $validated['bg_color'] ?? '#f8fafc',
            'title_color'  => $validated['title_color'] ?? '#2563eb',
            'sort_order'   => (int) ($validated['sort_order'] ?? 0),
            'is_active'    => true,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => "Card area {$area->name} berhasil ditambahkan.",
            'area'     => $area,
            'topology' => $this->topology->build(),
        ], 201);
    }

    /**
     * Update card area / POP EzyHome.
     */
    public function updateArea(Request $request, EzyHomeArea $area)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengubah Card Area / POP.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:ezyhome_areas,name,' . $area->id,
            'code'         => 'nullable|string|max:50',
            'description'  => 'nullable|string',
            'pos_x'        => 'nullable|numeric',
            'pos_y'        => 'nullable|numeric',
            'width'        => 'nullable|numeric|min:80',
            'height'       => 'nullable|numeric|min:80',
            'border_color' => 'nullable|string|max:20',
            'bg_color'     => 'nullable|string|max:20',
            'title_color'  => 'nullable|string|max:20',
            'sort_order'   => 'nullable|integer',
        ]);

        $area->update([
            'name'         => $validated['name'],
            'code'         => $validated['code'] ?? $area->code,
            'description'  => $validated['description'] ?? $area->description,
            'pos_x'        => isset($validated['pos_x']) ? (int) round($validated['pos_x']) : $area->pos_x,
            'pos_y'        => isset($validated['pos_y']) ? (int) round($validated['pos_y']) : $area->pos_y,
            'width'        => isset($validated['width']) ? (int) round($validated['width']) : $area->width,
            'height'       => isset($validated['height']) ? (int) round($validated['height']) : $area->height,
            'border_color' => $validated['border_color'] ?? $area->border_color,
            'bg_color'     => $validated['bg_color'] ?? $area->bg_color,
            'title_color'  => $validated['title_color'] ?? $area->title_color,
            'sort_order'   => isset($validated['sort_order']) ? (int) $validated['sort_order'] : $area->sort_order,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => "Card area {$area->name} berhasil diperbarui.",
            'area'     => $area->fresh(),
            'topology' => $this->topology->build(),
        ]);
    }

    /**
     * Hapus card area / POP EzyHome.
     * Posisi device di dalamnya di-NULL-kan, device tetap ada.
     */
    public function destroyArea(EzyHomeArea $area)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menghapus Card Area / POP.')) {
            return $forbidden;
        }

        $name = $area->name;

        // Lepaskan posisi device dari area ini (device tetap ada)
        EzyHomePosition::where('ezyhome_area_id', $area->id)
            ->update(['ezyhome_area_id' => null]);

        $area->delete();

        return response()->json([
            'success'  => true,
            'message'  => "Card area {$name} berhasil dihapus.",
            'topology' => $this->topology->build(),
        ]);
    }


    /**
     * Update bounds area / POP (resize card).
     */
    public function updateAreaBounds(Request $request, EzyHomeArea $area)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengubah area.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'pos_x'  => 'nullable|numeric',
            'pos_y'  => 'nullable|numeric',
            'width'  => 'nullable|numeric',
            'height' => 'nullable|numeric',
        ]);

        $area->update(array_filter($validated, fn ($v) => $v !== null));

        return response()->json([
            'success'  => true,
            'message'  => 'Area diperbarui.',
            'topology' => $this->topology->build(),
        ]);
    }

    /**
     * Simpan kustomisasi layout (stub).
     */
    public function saveMapLayout(Request $request)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menyimpan layout.')) {
            return $forbidden;
        }

        return response()->json([
            'success' => true,
            'message' => 'Layout disimpan.',
        ]);
    }

    /**
     * Reset kustomisasi layout (stub).
     */
    public function resetMapLayout(Request $request)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mereset layout.')) {
            return $forbidden;
        }

        return response()->json([
            'success' => true,
            'message' => 'Layout direset.',
        ]);
    }

    /**
     * Hapus relasi.
     */
    public function destroyConnection(EzyHomeConnection $connection)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menghapus relasi.')) {
            return $forbidden;
        }

        $connection->delete();

        return response()->json([
            'success'  => true,
            'message'  => 'Relasi berhasil dihapus.',
            'topology' => $this->topology->build(),
        ]);
    }
}