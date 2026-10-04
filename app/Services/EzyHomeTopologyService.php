<?php

namespace App\Services;

use App\Models\EzyHomeArea;
use App\Models\EzyHomeConnection;
use App\Models\EzyHomeDevice;
use App\Models\EzyHomePosition;
use Illuminate\Support\Collection;

/**
 * Service topology untuk EzyHome — mirror dari NetworkTopologyService.
 * Nggak nyentuh tabel monitoring jaringan sama sekali.
 */
class EzyHomeTopologyService
{
    private const DEVICE_DX    = 80;
    private const DEVICE_DY    = 100;
    private const AREA_ICON    = 44;
    private const AREA_PAD_LR  = 16;
    private const AREA_PAD_TOP = 70;
    private const AREA_PAD_BOT = 56;
    private const AREA_MIN_W   = 96;
    private const AREA_MARGIN  = 20;

    /**
     * Bangun payload topology untuk halaman EzyHome.
     */
    public function build(): array
    {
        // 1. Ambil area aktif
        $dbAreas = EzyHomeArea::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $areasById = [];
        foreach ($dbAreas as $a) {
            $areasById[$a->id] = $a;
        }

        // 2. Ambil semua device
        $devices = EzyHomeDevice::all()->keyBy('id');

        // 3. Ambil semua posisi
        $positions = EzyHomePosition::orderBy('id')->get();

        // 4. Ambil edges
        $rawConnections = EzyHomeConnection::all();

        $degree = [];
        $edges = [];
        foreach ($rawConnections as $c) {
            if (! isset($devices[$c->from_device_id]) || ! isset($devices[$c->to_device_id])) {
                continue;
            }

            $fromPosId = $c->from_position_id
                ? (int) $c->from_position_id
                : -1 * (int) $c->from_device_id;
            $toPosId = $c->to_position_id
                ? (int) $c->to_position_id
                : -1 * (int) $c->to_device_id;

            $edges[] = [
                'id'        => (int) $c->id,
                'from'      => $fromPosId,
                'to'        => $toPosId,
                'type'      => $c->connection_type ?: 'ethernet',
                'direction' => $c->direction ?: 'forward',
            ];
            $degree[$fromPosId] = ($degree[$fromPosId] ?? 0) + 1;
            $degree[$toPosId]   = ($degree[$toPosId] ?? 0) + 1;
        }

        // 5. Node per area
        $positionsByArea = [];
        foreach ($positions as $pos) {
            $areaId = $pos->ezyhome_area_id;
            if (! isset($positionsByArea[$areaId])) {
                $positionsByArea[$areaId] = [];
            }
            $positionsByArea[$areaId][] = $pos;
        }

        $areas = [];
        $maxRight = 800;
        $maxBottom = 600;

        foreach ($dbAreas as $area) {
            $areaPositions = $positionsByArea[$area->id] ?? [];

            $nodes = [];
            $count = count($areaPositions);
            $cols = max(1, (int) ceil(sqrt($count ?: 1)));

            foreach ($areaPositions as $i => $pos) {
                $device = $devices[$pos->ezyhome_device_id] ?? null;
                if (! $device) continue;

                $col = $i % $cols;
                $rowN = intdiv($i, $cols);

                $x = $pos->pos_x !== null
                    ? (int) $pos->pos_x
                    : $area->pos_x + 40 + ($col * self::DEVICE_DX);
                $y = $pos->pos_y !== null
                    ? (int) $pos->pos_y
                    : $area->pos_y + 60 + ($rowN * self::DEVICE_DY);

                $nodes[] = [
                    'id'        => (int) $pos->id,
                    'device_id' => (int) $device->id,
                    'name'      => $device->name,
                    'ip'        => $device->ip_address,
                    'icon'      => $device->icon_key,
                    'type'      => $device->type,
                    'protocol'  => $device->protocol,
                    'status'    => $device->status,
                    'pop'       => $device->pop_name ?: $area->name,
                    'degree'    => $degree[$pos->id] ?? 0,
                    'x'         => $x,
                    'y'         => $y,
                    'area'      => $area->name,
                ];
            }

            $maxRight = max($maxRight, $area->pos_x + $area->width);
            $maxBottom = max($maxBottom, $area->pos_y + $area->height);

            $areas[] = [
                'id'           => $area->id,
                'name'         => $area->name,
                'code'         => $area->code,
                'x'            => $area->pos_x,
                'y'            => $area->pos_y,
                'w'            => $area->width,
                'h'            => $area->height,
                'border_color' => $area->border_color ?: '#2563eb',
                'bg_color'     => $area->bg_color ?: '#f8fafc',
                'title_color'  => $area->title_color ?: '#2563eb',
                'nodes'        => $nodes,
            ];
        }

        return [
            'viewBox'       => sprintf(
                '0 0 %d %d',
                (int) ceil($maxRight + self::AREA_MARGIN),
                (int) ceil($maxBottom + self::AREA_MARGIN)
            ),
            'areas'         => $areas,
            'edges'         => $edges,
            'arrowSettings' => [
                'clearance'    => 28,
                'needle_size'  => 7,
                'stroke_width' => 2.2,
            ],
            'isCustomized'  => false,
        ];
    }

    /**
     * Daftar device EzyHome untuk panel kiri.
     */
    public function deviceList(): array
    {
        $devices = EzyHomeDevice::orderBy('name')->get()->keyBy('id');
        $positions = EzyHomePosition::orderBy('id')->get();

        $areasById = EzyHomeArea::pluck('name', 'id');

        $result = [];

        foreach ($positions as $pos) {
            $device = $devices[$pos->ezyhome_device_id] ?? null;
            if (! $device) continue;

            $areaName = $areasById[$pos->ezyhome_area_id] ?? null;

            $result[] = [
                'id'             => (int) $device->id,
                'position_id'    => (int) $pos->id,
                'name'           => $device->name,
                'ip'             => $device->ip_address,
                'icon'           => $device->icon_key,
                'type'           => $device->type,
                'protocol'       => $device->protocol,
                'status'         => $device->status,
                'router_version' => $device->router_version,
                'pop'            => $areaName ?: ($device->pop_name ?: 'Belum ditempatkan'),
                'area_id'        => $pos->ezyhome_area_id,
                'area'           => $areaName,
                'placed'         => true,
                'pos_x'          => $pos->pos_x,
                'pos_y'          => $pos->pos_y,
                'links'          => 0,
            ];
        }

        // Device yang belum punya posisi
        $deviceIdsWithPos = $positions->pluck('ezyhome_device_id')->unique()->all();
        foreach ($devices as $device) {
            if (in_array($device->id, $deviceIdsWithPos, true)) continue;

            $result[] = [
                'id'             => (int) $device->id,
                'position_id'    => null,
                'name'           => $device->name,
                'ip'             => $device->ip_address,
                'icon'           => $device->icon_key,
                'type'           => $device->type,
                'protocol'       => $device->protocol,
                'status'         => $device->status,
                'router_version' => $device->router_version,
                'pop'            => $device->pop_name ?: 'Belum ditempatkan',
                'area_id'        => null,
                'area'           => null,
                'placed'         => false,
                'pos_x'          => null,
                'pos_y'          => null,
                'links'          => 0,
            ];
        }

        return $result;
    }

    /**
     * Detail satu device EzyHome (untuk popup).
     */
    public function deviceDetail(EzyHomeDevice $device): array
    {
        $links = EzyHomeConnection::where(function ($q) use ($device) {
            $q->where('from_device_id', $device->id)
              ->orWhere('to_device_id', $device->id);
        })->count();

        return [
            'device' => [
                'id'         => $device->id,
                'name'       => $device->name,
                'ip_address' => $device->ip_address,
                'type'       => $device->type,
                'protocol'   => $device->protocol,
                'pop_name'   => $device->pop_name,
                'status'     => $device->status,
                'icon_type'  => $device->icon_key,
                'deskripsi'  => $device->deskripsi,
                'links'      => $links,
            ],
            'stats' => [
                'type'          => $device->type,
                'protocol'      => $device->protocol,
                'total_polls'   => (int) $device->polls,
                'responded_pct' => $device->responded_percentage,
                'missed_pct'    => $device->missed_percentage,
                'down_time'     => $device->down_time,
                'avg_delay'     => $device->avg_delay,
                'min_delay'     => $device->min_delay,
                'max_delay'     => $device->max_delay,
                'period'        => $device->period,
                'alerts_count'  => (int) $device->alerts_count,
                'last_poll'     => null,
            ],
            'logs' => [],
        ];
    }
}