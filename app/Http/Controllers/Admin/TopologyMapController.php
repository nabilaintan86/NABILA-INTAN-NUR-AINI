<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\TopologyMap;
use App\Models\TopologyMapArea;
use App\Models\TopologyMapConnection;
use App\Models\TopologyMapDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopologyMapController extends Controller
{
    /*
    |--------------------------------------------------------------------
    | MAPS (CRUD UTAMA)
    |--------------------------------------------------------------------
    */

    /**
     * List semua maps
     */
    public function index()
    {
        $maps = TopologyMap::withCount(['devices', 'areas'])
            ->orderBy('name')
            ->get();

        return view('admin.topology-maps.index', compact('maps'));
    }

    /**
     * Form tambah maps baru
     */
    public function create()
    {
        return view('admin.topology-maps.create');
    }

    /**
     * Simpan maps baru ke DB
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['slug']       = Str::slug($data['name']) . '-' . time();
        $data['created_by'] = auth()->id();
        $data['is_active']  = true;

        $map = TopologyMap::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Maps berhasil dibuat.',
                'map'     => $map,
            ]);
        }

        return redirect()
            ->route('admin.topology-maps.show', $map)
            ->with('status', 'Maps berhasil dibuat.');
    }

    /**
     * Halaman detail maps (topology view)
     */
    public function show(TopologyMap $topologyMap)
    {
        $topologyMap->load(['areas', 'devices.device', 'connections']);

        // Semua maps (buat tabs di halaman detail)
        $allMaps = TopologyMap::orderBy('name')->get();

        // Device yang BELUM masuk map manapun
        $usedDeviceIds = TopologyMapDevice::pluck('device_id');
        $availableDevices = Device::whereNotIn('id', $usedDeviceIds)
            ->orderBy('name')
            ->get();

        return view('admin.topology-maps.show', [
            'map'              => $topologyMap,
            'allMaps'          => $allMaps,
            'availableDevices' => $availableDevices,
        ]);
    }

    /**
     * Form edit maps
     */
    public function edit(TopologyMap $topologyMap)
    {
        return view('admin.topology-maps.edit', ['map' => $topologyMap]);
    }

    /**
     * Update maps
     */
    public function update(Request $request, TopologyMap $topologyMap)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $topologyMap->update($data);

        return redirect()
            ->route('admin.topology-maps.index')
            ->with('status', 'Maps berhasil diupdate.');
    }

    /**
     * Hapus maps (cascade ke areas, devices, connections)
     */
    public function destroy(TopologyMap $topologyMap)
    {
        $topologyMap->delete();

        if (request()->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()
            ->route('admin.topology-maps.index')
            ->with('status', 'Maps berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------
    | DEVICE DI DALAM MAP
    |--------------------------------------------------------------------
    */

    /**
     * Tambah device dari tabel `devices` ke dalam map
     * (1 device cuma boleh ada di 1 map — UNIQUE di DB)
     */
    public function storeDevice(Request $request, TopologyMap $topologyMap)
    {
        $data = $request->validate([
            'device_id'            => 'required|exists:devices,id',
            'topology_map_area_id' => 'nullable|exists:topology_map_areas,id',
            'pos_x'                => 'nullable|integer',
            'pos_y'                => 'nullable|integer',
        ]);

        // Cek: device udah ada di map lain?
        $exists = TopologyMapDevice::where('device_id', $data['device_id'])->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Device ini sudah ada di map lain. Satu device hanya boleh masuk satu map.',
            ], 422);
        }

        $data['topology_map_id'] = $topologyMap->id;

        $mapDevice = TopologyMapDevice::create($data);
        $mapDevice->load('device');

        return response()->json([
            'success'    => true,
            'message'    => 'Device berhasil ditambahkan ke map.',
            'map_device' => [
                'id'                   => $mapDevice->id,
                'device_id'            => $mapDevice->device_id,
                'name'                 => $mapDevice->device->name ?? '-',
                'ip_address'           => $mapDevice->device->ip_address ?? '-',
                'type'                 => $mapDevice->device->type ?? '-',
                'status'               => $mapDevice->device->status ?? 'unknown',
                'pos_x'                => $mapDevice->pos_x,
                'pos_y'                => $mapDevice->pos_y,
                'topology_map_area_id' => $mapDevice->topology_map_area_id,
            ],
        ]);
    }
    /**
     * Bikin device BARU + langsung masuk ke map
     * (form input manual, bukan pilih dari existing)
     */
    public function storeNewDevice(Request $request, TopologyMap $topologyMap)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'type'                 => 'required|string|max:50',
            'protocol'             => 'required|string|max:50',
            'ip_address'           => 'required|string|max:45',
            'router_version'       => 'nullable|string|max:255',
            'topology_map_area_id' => 'nullable|exists:topology_map_areas,id',
            'pos_x'                => 'nullable|integer',
            'pos_y'                => 'nullable|integer',
        ]);

        // Cek duplikat IP di tabel devices
        $existingIp = Device::where('ip_address', $data['ip_address'])->exists();
        if ($existingIp) {
            return response()->json([
                'success' => false,
                'message' => 'IP address sudah terdaftar di sistem.',
            ], 422);
        }

        // Bikin device baru di tabel `devices`
        $device = Device::create([
            'name'           => $data['name'],
            'type'           => $data['type'],
            'protocol'       => $data['protocol'],
            'ip_address'     => $data['ip_address'],
            'router_version' => $data['router_version'] ?? null,
            'status'         => 'unknown',
            'polls'          => 0,
        ]);
             $mapDevice = TopologyMapDevice::create([
            'topology_map_id'      => $topologyMap->id,
            'topology_map_area_id' => $data['topology_map_area_id'] ?? null,
            'device_id'            => $device->id,
            'pos_x'                => null,
            'pos_y'                => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device berhasil dibuat & ditambahkan ke map.',
            'map_device' => [
                'id'                   => $mapDevice->id,
                'device_id'            => $device->id,
                'name'                 => $device->name,
                'ip_address'           => $device->ip_address,
                'type'                 => $device->type,
                'status'               => $device->status ?? 'unknown',
                'pos_x'                => $mapDevice->pos_x,
                'pos_y'                => $mapDevice->pos_y,
                'topology_map_area_id' => $mapDevice->topology_map_area_id,
            ],
        ]);
    }

    /**
     * Update posisi device di canvas (drag & drop)
     */
    public function updateDevicePosition(Request $request, TopologyMap $topologyMap, TopologyMapDevice $mapDevice)
    {
        if ((int) $mapDevice->topology_map_id !== (int) $topologyMap->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $data = $request->validate([
            'pos_x'                => 'nullable|integer',
            'pos_y'                => 'nullable|integer',
            'topology_map_area_id' => 'nullable|exists:topology_map_areas,id',
        ]);

        $mapDevice->update($data);

        return response()->json(['success' => true]);
    }

    /**
     * Hapus device dari map
     */
    public function destroyDevice(TopologyMap $topologyMap, TopologyMapDevice $mapDevice)
    {
        if ((int) $mapDevice->topology_map_id !== (int) $topologyMap->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $mapDevice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Device berhasil dihapus dari map.',
        ]);
    }

    /*
    |--------------------------------------------------------------------
    | AREA / POP DI DALAM MAP
    |--------------------------------------------------------------------
    */

    public function storeArea(Request $request, TopologyMap $topologyMap)
    {
                $data = $request->validate([
            'name'         => 'required|string|max:255',
            'code'         => 'nullable|string|max:50',
            'description'  => 'nullable|string',
            'pos_x'        => 'nullable|integer',
            'pos_y'        => 'nullable|integer',
            'width'        => 'nullable|integer|min:120',
            'height'       => 'nullable|integer|min:80',
            'border_color' => 'nullable|string|max:20',
            'bg_color'     => 'nullable|string|max:20',
            'title_color'  => 'nullable|string|max:20',
        ]);

        $data['topology_map_id'] = $topologyMap->id;
        $data['width']  = $data['width']  ?? 180;
        $data['height'] = $data['height'] ?? 140;

        $area = TopologyMapArea::create($data);

        return response()->json([
            'success' => true,
            'message' => 'POP/Area berhasil ditambahkan.',
            'area'    => $area,
        ]);
    }

    public function updateArea(Request $request, TopologyMap $topologyMap, TopologyMapArea $mapArea)
    {
        if ((int) $mapArea->topology_map_id !== (int) $topologyMap->id) {
            return response()->json(['success' => false], 403);
        }

        $data = $request->validate([
            'name'         => 'sometimes|string|max:255',
            'code'         => 'nullable|string|max:50',
            'description'  => 'nullable|string',
            'pos_x'        => 'nullable|integer',
            'pos_y'        => 'nullable|integer',
            'width'        => 'nullable|integer|min:80',
            'height'       => 'nullable|integer|min:80',
            'border_color' => 'nullable|string|max:20',
            'bg_color'     => 'nullable|string|max:20',
            'title_color'  => 'nullable|string|max:20',
        ]);

        $mapArea->update($data);

        return response()->json(['success' => true, 'area' => $mapArea->fresh()]);
    }

    public function destroyArea(TopologyMap $topologyMap, TopologyMapArea $mapArea)
    {
        if ((int) $mapArea->topology_map_id !== (int) $topologyMap->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $mapArea->delete();

        return response()->json([
            'success' => true,
            'message' => 'POP/Area berhasil dihapus.',
        ]);
    }

    public function storeConnection(Request $request, TopologyMap $topologyMap)
    {
        $data = $request->validate([
            'from_map_device_id' => 'required|exists:topology_map_devices,id',
            'to_map_device_id'   => 'required|exists:topology_map_devices,id|different:from_map_device_id',
            'connection_type'    => 'nullable|string|max:50',
            'direction'          => 'nullable|in:forward,reverse,bidirectional,none',
            'label'              => 'nullable|string|max:255',
        ]);

        // Cek: 2 device ini udah pernah dihubungkan?
        $exists = TopologyMapConnection::where('topology_map_id', $topologyMap->id)
            ->where(function ($q) use ($data) {
                $q->where(function ($q2) use ($data) {
                    $q2->where('from_map_device_id', $data['from_map_device_id'])
                       ->where('to_map_device_id', $data['to_map_device_id']);
                })->orWhere(function ($q2) use ($data) {
                    $q2->where('from_map_device_id', $data['to_map_device_id'])
                       ->where('to_map_device_id', $data['from_map_device_id']);
                });
            })
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Kedua device ini sudah terhubung.',
            ], 422);
        }

        $data['topology_map_id'] = $topologyMap->id;

        $conn = TopologyMapConnection::create($data);

        return response()->json([
            'success'    => true,
            'message'    => 'Relasi berhasil ditambahkan.',
            'connection' => $conn,
        ]);
    }

    public function destroyConnection(TopologyMap $topologyMap, TopologyMapConnection $mapConnection)
    {
        if ((int) $mapConnection->topology_map_id !== (int) $topologyMap->id) {
            return response()->json(['success' => false], 403);
        }

        $mapConnection->delete();

        return response()->json(['success' => true]);
    }
    public function showDevice(TopologyMap $topologyMap, TopologyMapDevice $mapDevice)
    {
        if ((int) $mapDevice->topology_map_id !== (int) $topologyMap->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $mapDevice->load(['device', 'area']);
        $device = $mapDevice->device;

        // Recent log extract (opsional — kalau tabel monitoring_logs ada)
        $logs = [];
        if ($device && \Illuminate\Support\Facades\Schema::hasTable('monitoring_logs')) {
            try {
                $logs = \DB::table('monitoring_logs')
                    ->where('device_id', $device->id)
                    ->latest()
                    ->limit(20)
                    ->get()
                    ->map(function ($log) {
                        return [
                            'time'    => \Carbon\Carbon::parse($log->created_at)->format('d/m H:i:s'),
                            'status'  => strtolower($log->status ?? 'info'),
                            'message' => $log->message ?? '-',
                        ];
                    })->toArray();
            } catch (\Exception $e) {
                $logs = [];
            }
        }

        return response()->json([
            'success' => true,
            'device'  => [
                'id'                   => $mapDevice->id,
                'device_id'            => $mapDevice->device_id,
                'name'                 => $device->name ?? '-',
                'ip_address'           => $device->ip_address ?? '-',
                'type'                 => $device->type ?? '-',
                'protocol'             => $device->protocol ?? '-',
                'status'               => $device->status ?? 'unknown',
                'router_version'       => $device->router_version ?? null,
                'pop_name'             => $device->pop_name ?? null,
                'topology_map_area_id' => $mapDevice->topology_map_area_id,
                'area_name'            => $mapDevice->area->name ?? null,
                'pos_x'                => $mapDevice->pos_x,
                'pos_y'                => $mapDevice->pos_y,

                // Statistics
                'polls'                => $device->polls ?? 0,
                'responded_percentage' => $device->responded_percentage ?? 0,
                'missed_percentage'    => $device->missed_percentage ?? 0,
                'down_time'            => $device->down_time ?? null,
                'avg_delay'            => $device->avg_delay ?? 0,
                'min_delay'            => $device->min_delay ?? 0,
                'max_delay'            => $device->max_delay ?? 0,
                'uptime'               => $device->uptime ?? null,
                'last_poll'            => $device->updated_at ? $device->updated_at->format('d/m/y H:i:s') : '-',
            ],
            'logs' => $logs,
        ]);
    }
    public function updateDevice(Request $request, TopologyMap $topologyMap, TopologyMapDevice $mapDevice)
    {
        if ((int) $mapDevice->topology_map_id !== (int) $topologyMap->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'type'                 => 'required|string|max:50',
            'protocol'             => 'required|string|max:50',
            'ip_address'           => 'required|string|max:45',
            'router_version'       => 'nullable|string|max:255',
            'topology_map_area_id' => 'nullable|exists:topology_map_areas,id',
        ]);

        // Cek duplikat IP (kecuali device ini sendiri)
        $existingIp = Device::where('ip_address', $data['ip_address'])
            ->where('id', '!=', $mapDevice->device_id)
            ->exists();

        if ($existingIp) {
            return response()->json([
                'success' => false,
                'message' => 'IP address sudah dipakai device lain.',
            ], 422);
        }

        // Update tabel devices
        $mapDevice->device->update([
            'name'           => $data['name'],
            'type'           => $data['type'],
            'protocol'       => $data['protocol'],
            'ip_address'     => $data['ip_address'],
            'router_version' => $data['router_version'] ?? null,
        ]);

        // Update area kalau berubah
        $mapDevice->update([
            'topology_map_area_id' => $data['topology_map_area_id'] ?? null,
        ]);

        $mapDevice->load('device');

        return response()->json([
            'success' => true,
            'message' => 'Device berhasil diupdate.',
            'device'  => [
                'id'                   => $mapDevice->id,
                'device_id'            => $mapDevice->device_id,
                'name'                 => $mapDevice->device->name,
                'ip_address'           => $mapDevice->device->ip_address,
                'type'                 => $mapDevice->device->type,
                'status'               => $mapDevice->device->status ?? 'unknown',
                'topology_map_area_id' => $mapDevice->topology_map_area_id,
                'pos_x'                => $mapDevice->pos_x,
                'pos_y'                => $mapDevice->pos_y,
            ],
        ]);
    }
    public function updateConnectionDirection(Request $request, TopologyMap $topologyMap, TopologyMapConnection $mapConnection)
    {
        if ((int) $mapConnection->topology_map_id !== (int) $topologyMap->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $data = $request->validate([
            'direction'       => 'required|in:forward,reverse,bidirectional,none',
            'connection_type' => 'nullable|string|max:50',
        ]);

        $mapConnection->update($data);

        return response()->json([
            'success'    => true,
            'message'    => 'Relasi berhasil diupdate.',
            'connection' => $mapConnection->fresh(),
        ]);
    }   

    public function mapData(TopologyMap $topologyMap)
    {
        $topologyMap->load(['areas', 'devices.device', 'connections']);

        return response()->json([
            'map' => [
                'id'          => $topologyMap->id,
                'name'        => $topologyMap->name,
                'description' => $topologyMap->description,
            ],
            'areas' => $topologyMap->areas,
            'devices' => $topologyMap->devices->map(function ($d) {
                return [
                    'id'                   => $d->id,
                    'device_id'            => $d->device_id,
                    'name'                 => $d->device->name ?? '-',
                    'ip_address'           => $d->device->ip_address ?? '-',
                    'type'                 => $d->device->type ?? '-',
                    'status'               => $d->device->status ?? 'unknown',
                    'pos_x'                => $d->pos_x,
                    'pos_y'                => $d->pos_y,
                    'topology_map_area_id' => $d->topology_map_area_id,
                    'area_name'            => $d->area->name ?? null,
                ];
            }),
            'connections' => $topologyMap->connections->map(function ($c) {
                return [
                    'id'                 => $c->id,
                    'from_map_device_id' => $c->from_map_device_id,
                    'to_map_device_id'   => $c->to_map_device_id,
                    'connection_type'    => $c->connection_type,
                    'direction'          => $c->direction,
                    'label'              => $c->label,
                ];
            }),
        ]);
    }

    /**
     * List semua device yang BELUM masuk map manapun
     * (buat dropdown "Tambah Device" — biar user tau mana yang available)
     */
    public function availableDevices(TopologyMap $topologyMap)
    {
        $usedDeviceIds = TopologyMapDevice::pluck('device_id');

        $available = Device::whereNotIn('id', $usedDeviceIds)
            ->orderBy('name')
            ->get()
            ->map(function ($d) {
                return [
                    'id'         => $d->id,
                    'name'       => $d->name,
                    'ip_address' => $d->ip_address,
                    'type'       => $d->type,
                    'status'     => $d->status ?? 'unknown',
                ];
            });

        return response()->json(['devices' => $available]);
    }
    /**
     * Batch save semua posisi (area + device)
     */
    public function saveLayout(Request $request, TopologyMap $topologyMap)
    {
        $data = $request->validate([
            'areas'              => 'nullable|array',
            'areas.*.id'         => 'required|integer',
            'areas.*.pos_x'      => 'required|integer',
            'areas.*.pos_y'      => 'required|integer',
            'areas.*.width'      => 'nullable|integer',
            'areas.*.height'     => 'nullable|integer',
            'devices'            => 'nullable|array',
            'devices.*.id'       => 'required|integer',
            'devices.*.pos_x'    => 'required|integer',
            'devices.*.pos_y'    => 'required|integer',
        ]);

        foreach ($data['areas'] ?? [] as $a) {
            TopologyMapArea::where('id', $a['id'])
                ->where('topology_map_id', $topologyMap->id)
                ->update([
                    'pos_x'  => $a['pos_x'],
                    'pos_y'  => $a['pos_y'],
                    'width'  => $a['width'] ?? null,
                    'height' => $a['height'] ?? null,
                ]);
        }

        foreach ($data['devices'] ?? [] as $d) {
            TopologyMapDevice::where('id', $d['id'])
                ->where('topology_map_id', $topologyMap->id)
                ->update([
                    'pos_x' => $d['pos_x'],
                    'pos_y' => $d['pos_y'],
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Layout berhasil disimpan.',
        ]);
    }
}