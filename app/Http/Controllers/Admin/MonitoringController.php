<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceConnection;
use App\Models\MapSetting;
use App\Models\MonitoringLog;
use App\Models\NetworkArea;
use App\Services\DeviceCsvService;
use App\Services\DeviceRegistrar;
use App\Services\NetworkTopologyService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MonitoringController extends Controller
{
    public function __construct(
        private NetworkTopologyService $topologyService,
        private DeviceRegistrar $deviceRegistrar,
    ) {
    }

    /**
     * Gate konfigurasi topology.
     *
     * Semua endpoint yang MENGUBAH map (posisi device, relasi, area, device
     * itu sendiri) hanya boleh dipakai Super Admin. Admin/Staff tetap dapat
     * melihat map, membuka detail device, dan memakai seluruh fitur monitoring
     * (status UP/DOWN, log, refresh) karena endpoint-nya tidak memakai gate ini.
     *
     * Mengembalikan 403 JSON agar pesan error bisa ditampilkan di halaman.
     */
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

    public function index(DeviceCsvService $source)
    {
        // Ambil data perangkat dari database (yang sudah disinkronkan dengan devices.csv)
        $devices = Device::all();
        if ($devices->isEmpty()) {
            $devices = $source->officialDevices();
        }

        $totalUp      = $devices->where('status', 'up')->count();
        $totalDown    = $devices->where('status', 'down')->count();
        $totalWarning = $devices->where('status', 'warning')->count();
        $totalUnknown = $devices->whereNotIn('status', ['up', 'down', 'warning'])->count();
        $totalServicesDown = 0;

        // Ambil seluruh relasi koneksi antar-perangkat untuk tabel relasi
        $connections = DeviceConnection::with(['fromDevice', 'toDevice'])
            ->orderBy('id')
            ->get();

        // Ambil seluruh card area / POP WhatsUp Gold
        $networkAreas = NetworkArea::withCount('devices')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();


        // Ambil seluruh card area / POP EzyHome
        $ezyhomeAreas = \App\Models\EzyHomeArea::withCount('positions')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        // Riwayat log pemantauan terbaru
        $logs = MonitoringLog::with('device')
            ->orderByDesc('checked_at')
            ->orderByDesc('id')
            ->limit(50)
            ->get();

                    return view('admin.index', compact(
            'devices',
            'totalUp',
            'totalDown',
            'totalUnknown',
            'totalWarning',
            'totalServicesDown',
            'connections',
            'networkAreas',
            'ezyhomeAreas',
            'logs'
        ) + [
            'deviceSource' => $source->officialSummary(),
            'topology'     => $this->topologyService->build(),
            // Panel "Daftar Device" (kiri map) + form tambah/edit device.
            'deviceList'   => $this->topologyService->deviceList(),
            'deviceTypes'  => \App\Helpers\DeviceType::types(),
            'protocols'    => \App\Helpers\DeviceType::protocols(),
        ]);
    }

    public function data()
    {
        return response()->json($this->topologyService->build());
    }

    /**
     * JSON daftar device untuk panel "Daftar Device".
     *
     * Dipanggil panel kiri map agar device baru yang dibuat dari halaman ini
     * (atau dari Kelola Router) langsung muncul tanpa reload penuh, dan agar
     * status UP/DOWN pada panel selalu sinkron dengan hasil monitoring.
     */
    public function devices()
    {
        return response()->json([
            'success' => true,
            'devices' => $this->topologyService->deviceList(),
        ]);
    }

    /**
     * Detail satu device untuk popup pada MAP RADNET.
     * Read-only: tidak menjalankan ping / mengubah status apa pun.
     */
    public function device(Device $device)
    {
        return response()->json(
            $this->topologyService->deviceDetail($device)
        );
    }

    /**
     * Tambah perangkat baru langsung dari workspace Map (Super Admin).
     *
     * Memakai DeviceRegistrar yang sama dengan halaman Kelola Router, jadi
     * record `device` yang dihasilkan identik dan otomatis ikut monitoring.
     * Balasana JSON karena pemanggilnya panel Device (bukan form biasa).
     */
    public function storeDevice(Request $request)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menambah perangkat baru.')) {
            return $forbidden;
        }

        $device = $this->deviceRegistrar->store($request);

        return response()->json([
            'success'   => true,
            'message'   => "Perangkat {$device->name} berhasil ditambahkan.",
            'device'    => $this->payloadForDevice($device),
            'devices'   => $this->topologyService->deviceList(),
            'topology'  => $this->topologyService->build(),
        ], 201);
    }

    /**
     * Edit perangkat yang sudah ada dari workspace Map (Super Admin).
     *
     * Meng-update record device yang SAMA (route model binding) — tidak pernah
     * membuat device baru/duplikat.
     */
    public function updateDevice(Request $request, Device $device)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengubah data perangkat.')) {
            return $forbidden;
        }

        $this->deviceRegistrar->update($request, $device);
        $device->refresh();

        return response()->json([
            'success'  => true,
            'message'  => "Data perangkat {$device->name} berhasil diperbarui.",
            'device'   => $this->payloadForDevice($device),
            'devices'  => $this->topologyService->deviceList(),
            'topology' => $this->topologyService->build(),
        ]);
    }

    /**
     * Hapus perangkat beserta relasinya (Super Admin).
     *
     * Relasi ikut terhapus lewat cascade FK `device_connections`, sehingga
     * tidak ada edge yatim yang menggantung di map.
     */
    public function destroyDevice(Device $device)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menghapus perangkat.')) {
            return $forbidden;
        }

        $name = $device->name;
        $device->delete();

        return response()->json([
            'success'  => true,
            'message'  => "Perangkat {$name} berhasil dihapus.",
            'devices'  => $this->topologyService->deviceList(),
            'topology' => $this->topologyService->build(),
        ]);
    }


    /**
     * Hapus SATU POSISI device dari map (device tetap ada di DB).
     * Dipakai saat device dipakai di banyak POP — hapus per-POP.
     */
    public function destroyPosition(\App\Models\DevicePosition $position)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menghapus posisi perangkat.')) {
            return $forbidden;
        }

        $device = $position->device;
        $deviceName = $device ? $device->name : ('#' . $position->device_id);

        // Hapus edge yang endpoint-nya posisi ini (kiri atau kanan).
        \App\Models\DeviceConnection::where('from_position_id', $position->id)
            ->orWhere('to_position_id', $position->id)
            ->delete();

        $position->delete();

        return response()->json([
            'success'  => true,
            'message'  => "Posisi perangkat {$deviceName} berhasil dihapus dari POP ini.",
            'devices'  => $this->topologyService->deviceList(),
            'topology' => $this->topologyService->build(),
        ]);
    }

    /**
     * Simpan koordinat posisi perangkat hasil drag-and-drop.
     *
     * `network_area_id` bersifat opsional: ketika Super Admin meletakkan device
     * (drag dari panel Daftar Device, atau drag node yang sebelumnya di luar
     * area), area/POP tempat device itu diletakkan ikut disimpan memakai kolom
     * `device.network_area_id` yang sudah dipakai NetworkTopologyService untuk
     * pengelompokan panel.
     */
    public function updatePosition(Request $request, Device $device)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengatur posisi perangkat pada map.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'pos_x'           => 'required|numeric',
            'pos_y'           => 'required|numeric',
            'network_area_id' => 'nullable|integer|exists:network_areas,id',
            'position_id'     => 'nullable|integer|exists:device_positions,id',
        ]);

        $positionId = $validated['position_id'] ?? null;

        $assignedArea = null;
        $areaId = null;

        if (array_key_exists('network_area_id', $validated)) {
            $area = $validated['network_area_id']
                ? NetworkArea::find($validated['network_area_id'])
                : null;

            if ($area) {
                $areaId = $area->id;
                $assignedArea = $area->name;
            }
        }

        if ($positionId) {
            // UPDATE posisi yang sudah ada
            $pos = \App\Models\DevicePosition::find($positionId);
            if ($pos) {
                $pos->update([
                    'pos_x'           => (int) round($validated['pos_x']),
                    'pos_y'           => (int) round($validated['pos_y']),
                    'network_area_id' => $areaId,
                ]);
            }
        } else {
            // BELUM punya posisi → CREATE posisi baru (multi-position)
            \App\Models\DevicePosition::create([
                'device_id'       => $device->id,
                'network_area_id' => $areaId,
                'pos_x'           => (int) round($validated['pos_x']),
                'pos_y'           => (int) round($validated['pos_y']),
            ]);

            // Update juga legacy field di device (untuk kompatibilitas)
            $device->update([
                'pos_x'          => (int) round($validated['pos_x']),
                'pos_y'          => (int) round($validated['pos_y']),
                'network_area_id' => $areaId,
                'map_positioned' => true,
            ]);
        }

        return response()->json([
            'success'         => true,
            'message'         => "Posisi perangkat {$device->name} berhasil diperbarui.",
            'network_area_id' => $areaId,
            'area'            => $assignedArea,
            'devices'         => $this->topologyService->deviceList(),
            'topology'        => $this->topologyService->build(),
        ]);
    }
    /**
     * Reset semua posisi perangkat ke tata letak default.
     */
    public function resetPositions()
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mereset posisi perangkat.')) {
            return $forbidden;
        }

        Device::query()->update([
            'pos_x'          => null,
            'pos_y'          => null,
            'map_positioned' => false,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Semua posisi perangkat berhasil dikembalikan ke default.',
            'devices'  => $this->topologyService->deviceList(),
            'topology' => $this->topologyService->build(),
        ]);
    }

    /**
     * Ubah arah panah relasi (-> forward, <- reverse, <-> bidirectional, -- none).
     */
    public function updateDirection(Request $request, DeviceConnection $connection)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengubah arah panah relasi.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'direction' => 'required|string|in:forward,reverse,bidirectional,none',
        ]);

        $connection->update([
            'direction' => $validated['direction'],
        ]);

        return response()->json([
            'success'   => true,
            'direction' => $connection->direction,
            'symbol'    => $connection->arrow_symbol,
            'label'     => $connection->direction_label,
            'message'   => "Arah panah koneksi berhasil diubah ke {$connection->arrow_symbol} ({$connection->direction_label}).",
        ]);
    }

    /**
     * Simpan kustomisasi layout map lengkap oleh Super Admin.
     * Mengatur ukuran & posisi kotak pembatas router (area), posisi perangkat, dan jarak jarum panah.
     */
    public function saveMapLayout(Request $request)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang memiliki hak akses untuk menyimpan kustomisasi layout map.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'areas'                       => 'nullable|array',
            'areas.*.name'                => 'required|string',
            'areas.*.x'                   => 'required|numeric',
            'areas.*.y'                   => 'required|numeric',
            'areas.*.w'                   => 'required|numeric',
            'areas.*.h'                   => 'required|numeric',
            'devices'                     => 'nullable|array',
            'devices.*.id'                => 'required|integer',
            'devices.*.x'                 => 'required|numeric',
            'devices.*.y'                 => 'required|numeric',
            'devices.*.area'              => 'nullable|string',
            'arrow_settings'              => 'nullable|array',
            'arrow_settings.clearance'    => 'nullable|numeric',
            'arrow_settings.needle_size'  => 'nullable|numeric',
            'arrow_settings.stroke_width' => 'nullable|numeric',
        ]);

        // Simpan posisi perangkat ke tabel device jika ada
        if (!empty($validated['devices'])) {
            foreach ($validated['devices'] as $d) {
                Device::where('id', $d['id'])->update([
                    'pos_x'          => (int) round($d['x']),
                    'pos_y'          => (int) round($d['y']),
                    'map_positioned' => true,
                ]);
            }
        }

        // Simpan dimensi dan koordinat area ke tabel network_areas
        if (!empty($validated['areas'])) {
            foreach ($validated['areas'] as $ar) {
                NetworkArea::where('name', $ar['name'])->update([
                    'pos_x'  => (int) round($ar['x']),
                    'pos_y'  => (int) round($ar['y']),
                    'width'  => (int) round($ar['w']),
                    'height' => (int) round($ar['h']),
                ]);
            }
        }

        // Simpan seluruh konfigurasi area & jarum panah ke MapSetting
        MapSetting::set('topology_2026_layout', [
            'areas'          => $validated['areas'] ?? [],
            'arrow_settings' => $validated['arrow_settings'] ?? [
                'clearance'    => 28,
                'needle_size'  => 7,
                'stroke_width' => 2.2,
            ],
            'saved_at'       => now()->toDateTimeString(),
            'saved_by'       => auth()->user()->name,
        ], auth()->id());

        return response()->json([
            'success'  => true,
            'message'  => 'Konfigurasi layout map 2026 (pembatas router, posisi perangkat, dan jarum panah) berhasil disimpan secara permanen oleh Super Admin.',
            'topology' => $this->topologyService->build(),
        ]);
    }

    /**
     * Tambah Card Area / POP Baru (WhatsUp Gold 2026)
     */
    public function storeArea(Request $request)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menambah Card Area / POP.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:network_areas,name',
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

        $area = NetworkArea::create([
            'name'         => $validated['name'],
            'code'         => $validated['code'] ?? null,
            'description'  => $validated['description'] ?? null,
            'pos_x'        => (int) ($validated['pos_x'] ?? 50),
            'pos_y'        => (int) ($validated['pos_y'] ?? 50),
            'width'        => (int) ($validated['width'] ?? 240),
            'height'       => (int) ($validated['height'] ?? 200),
            'border_color' => $validated['border_color'] ?? '#1a8024',
            'bg_color'     => $validated['bg_color'] ?? '#f8fafc',
            'title_color'  => $validated['title_color'] ?? '#1a8024',
            'sort_order'   => (int) ($validated['sort_order'] ?? (NetworkArea::max('sort_order') + 1)),
            'is_active'    => true,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => "Card area {$area->name} berhasil ditambahkan.",
                'area'     => $area,
                'topology' => $this->topologyService->build(),
            ]);
        }

        return redirect()->route('admin.monitoring')->with('success', "Card area {$area->name} berhasil ditambahkan.");
    }

    /**
     * Edit Card Area / POP (Ubah Nama, Ukuran WxH, Koordinat XY, dan Warna)
     */
    public function updateArea(Request $request, NetworkArea $area)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengubah Card Area / POP.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:network_areas,name,' . $area->id,
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

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => "Card area {$area->name} berhasil diperbarui.",
                'area'     => $area->fresh(),
                'topology' => $this->topologyService->build(),
            ]);
        }

        return redirect()->route('admin.monitoring')->with('success', "Card area {$area->name} berhasil diperbarui.");
    }

    /**
     * Hapus Card Area / POP
     */
    public function destroyArea(Request $request, NetworkArea $area)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menghapus Card Area / POP.')) {
            return $forbidden;
        }

        $name = $area->name;
        // Lepaskan relasi perangkat
        Device::where('network_area_id', $area->id)->update(['network_area_id' => null]);
        $area->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => "Card area {$name} berhasil dihapus.",
                'topology' => $this->topologyService->build(),
            ]);
        }

        return redirect()->route('admin.monitoring')->with('success', "Card area {$name} berhasil dihapus.");
    }

    /**
     * Update Cepat Ukuran & Koordinat Card Area (dipanggil via drag / resize di SVG canvas)
     */
    public function updateAreaBounds(Request $request, NetworkArea $area)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengubah ukuran / posisi area map.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'pos_x'  => 'nullable|numeric',
            'pos_y'  => 'nullable|numeric',
            'width'  => 'nullable|numeric|min:60',
            'height' => 'nullable|numeric|min:60',
        ]);

        $data = [];
        if (isset($validated['pos_x']))  $data['pos_x']  = (int) round($validated['pos_x']);
        if (isset($validated['pos_y']))  $data['pos_y']  = (int) round($validated['pos_y']);
        if (isset($validated['width']))  $data['width']  = (int) round($validated['width']);
        if (isset($validated['height'])) $data['height'] = (int) round($validated['height']);

        if (!empty($data)) {
            $area->update($data);
        }

        return response()->json([
            'success' => true,
            'message' => "Ukuran / posisi area {$area->name} berhasil diperbarui.",
            'area'    => $area->fresh(),
        ]);
    }

    /**
     * Reset seluruh layout map ke default bawaan RADNET 2026 (Super Admin).
     */
    public function resetMapLayout(Request $request)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang berhak mereset layout map.')) {
            return $forbidden;
        }

        MapSetting::where('key', 'topology_2026_layout')->delete();

        Device::query()->update([
            'pos_x'          => null,
            'pos_y'          => null,
            'map_positioned' => false,
        ]);

        return response()->json([
            'success'  => true,
            'message'  => 'Layout map berhasil dikembalikan ke standar bawaan RADNET Wireless Network 2026.',
            'topology' => $this->topologyService->build(),
        ]);
    }

    /**
     * Tambah relasi koneksi baru antar-perangkat.
     *
     * Dipakai oleh panel Relasi Device (tab Relasi) maupun oleh mode
     * "Hubungkan" di map: klik perangkat sumber lalu perangkat tujuan. Keduanya
     * menulis ke tabel `device_connections` yang sama.
     */
    public function storeConnection(Request $request)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menambah relasi perangkat.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'from_device_id'   => 'required|exists:devices,id',
            'to_device_id'     => 'required|exists:devices,id|different:from_device_id',
            'from_position_id' => 'nullable|integer|exists:device_positions,id',
            'to_position_id'   => 'nullable|integer|exists:device_positions,id',
            'connection_type'  => 'nullable|string|max:50',
            'direction'        => 'required|string|in:forward,reverse,bidirectional,none',
        ]);

        $conn = DeviceConnection::updateOrCreate(
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

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Relasi perangkat berhasil ditambahkan.',
                'connection' => $conn->load(['fromDevice', 'toDevice']),
                'topology'   => $this->topologyService->build(),
                'devices'    => $this->topologyService->deviceList(),
            ]);
        }

        return redirect()->route('admin.monitoring')->with('success', 'Relasi perangkat berhasil ditambahkan.');
    }
    /**
     * Edit data relasi koneksi antar-perangkat.
     */
    public function updateConnection(Request $request, DeviceConnection $connection)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat mengubah relasi perangkat.')) {
            return $forbidden;
        }

        $validated = $request->validate([
            'from_device_id'  => 'required|exists:devices,id',
            'to_device_id'    => 'required|exists:devices,id|different:from_device_id',
            'connection_type' => 'nullable|string|max:50',
            'direction'       => 'required|string|in:forward,reverse,bidirectional,none',
        ]);

        $connection->update([
            'from_device_id'  => $validated['from_device_id'],
            'to_device_id'    => $validated['to_device_id'],
            'connection_type' => $validated['connection_type'] ?? $connection->connection_type,
            'direction'       => $validated['direction'],
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'    => true,
                'message'    => 'Relasi koneksi berhasil diperbarui.',
                'connection' => $connection->fresh()->load(['fromDevice', 'toDevice']),
                'topology'   => $this->topologyService->build(),
            ]);
        }

        return redirect()->route('admin.monitoring')->with('success', 'Relasi koneksi berhasil diperbarui.');
    }

    /**
     * Hapus relasi antar-perangkat.
     */
    public function destroyConnection(Request $request, DeviceConnection $connection)
    {
        if ($forbidden = $this->guardSuperAdmin('Hanya Super Admin yang dapat menghapus relasi perangkat.')) {
            return $forbidden;
        }

        $connection->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => 'Relasi koneksi berhasil dihapus.',
                'topology' => $this->topologyService->build(),
            ]);
        }

        return redirect()->route('admin.monitoring')->with('success', 'Relasi koneksi berhasil dihapus.');
    }

    /**
     * Ringkasan satu device untuk panel Daftar Device / popup detail.
     */
    private function payloadForDevice(Device $device): array
    {
        $areas = NetworkArea::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->pluck('name', 'id');

        return [
            'id'             => (int) $device->id,
            'name'           => $device->name,
            'ip'             => $device->ip_address,
            'icon'           => $device->icon_key,
            'type'           => $device->display_type,
            'protocol'       => $device->display_protocol,
            'status'         => $device->status,
            'router_version' => $device->router_version,
            'pop'            => $device->pop_name ?: ($areas[$device->network_area_id] ?? 'Belum ditempatkan'),
            'area_id'        => $device->network_area_id ? (int) $device->network_area_id : null,
            'area'           => $areas[$device->network_area_id] ?? null,
            'placed'         => (bool) $device->map_positioned
                && $device->pos_x !== null
                && $device->pos_y !== null,
            'pos_x'          => $device->pos_x !== null ? (int) $device->pos_x : null,
            'pos_y'          => $device->pos_y !== null ? (int) $device->pos_y : null,
            'links'          => $device->outboundConnections()->count()
                + $device->inboundConnections()->count(),
        ];
    }
}