<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceConnection;

class MapController extends Controller
{
    public function index()
    {
        return view('admin.map', [
            'groups' => $this->buildGroups(),
        ]);
    }

    public function data()
    {
        return response()->json($this->buildGroups());
    }

    private function buildGroups(): array
    {
        $devices = Device::whereNotNull('pop_name')
            ->select('id', 'name', 'ip_address', 'status', 'icon_type', 'pop_name')
            ->get();

        $connections = DeviceConnection::select('from_device_id', 'to_device_id', 'connection_type')
            ->get()
            ->map(fn ($c) => [
                'from' => $c->from_device_id,
                'to'   => $c->to_device_id,
                'type' => $c->connection_type,
            ])
            ->toArray();

        $connectionIndex = [];
        foreach ($connections as $conn) {
            $connectionIndex[$conn['from']][$conn['to']] = true;
            $connectionIndex[$conn['to']][$conn['from']] = true;
        }

        $groups = [];

        foreach ($devices as $device) {
            $pop = $device->pop_name;

            if (! isset($groups[$pop])) {
                $groups[$pop] = [
                    'pop_name' => $pop,
                    'devices'  => [],
                ];
            }

            $neighbors = array_keys($connectionIndex[$device->id] ?? []);
            $totalConnections = count($neighbors);

            $groups[$pop]['devices'][] = [
                'id'          => $device->id,
                'name'        => $device->name,
                'ip'          => $device->ip_address,
                'status'      => $device->status,
                'icon_type'   => $device->icon_type ?? 'server',
                'connections' => $totalConnections,
            ];
        }

        ksort($groups);

        return [
            'groups'      => $groups,
            'connections' => $connections,
        ];
    }
}
