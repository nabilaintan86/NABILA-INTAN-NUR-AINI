<?php

namespace App\Services;

use App\Helpers\DeviceType;
use App\Models\Device;
use Illuminate\Http\Request;

/**
 * Mengelola pembuatan & pembaruan record Device dari halaman Kelola Router
 * maupun dari workspace Map (panel Daftar Device).
 *
 * Memakai model Device dan helper DeviceType yang sama dengan yang dipakai
 * halaman lain, jadi record yang dihasilkan konsisten dan otomatis ikut
 * monitoring.
 */
class DeviceRegistrar
{
    /**
     * Buat device baru dari request.
     */
    public function store(Request $request): Device
    {
        $data = $this->validated($request);

        $device = new Device();
        $this->fill($device, $data);
        $device->save();

        return $device->fresh();
    }

    /**
     * Perbarui device yang sudah ada.
     */
    public function update(Request $request, Device $device): Device
    {
        $data = $this->validated($request, $device);

        $this->fill($device, $data);
        $device->save();

        return $device->fresh();
    }

    /**
     * Aturan validasi untuk form device.
     *
     * @return array<string, mixed>
     */
    protected function validated(Request $request, ?Device $device = null): array
    {
        return $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'nullable|string|max:64',
            'protocol'       => 'nullable|string|max:32',
            'ip_address'     => 'nullable|string|max:64',
            'router_version' => 'nullable|string|max:255',
            'icon_type'      => 'nullable|string|max:64',
            'pop_name'       => 'nullable|string|max:255',
            'network_area_id' => 'nullable|integer|exists:network_areas,id',
            'deskripsi'      => 'nullable|string',
            'penyebab'       => 'nullable|string',
            'gambar'         => 'nullable|string|max:255',
        ]);
    }

    /**
     * Isi atribut device dari data valid.
     *
     * @param  array<string, mixed>  $data
     */
    protected function fill(Device $device, array $data): void
    {
        $device->name           = $data['name'];
        $device->ip_address     = $data['ip_address'] ?? $device->ip_address;

        $device->type           = DeviceType::normalizeType(
            $data['type'] ?? $device->type,
            $data['icon_type'] ?? $device->icon_type
        );
        $device->protocol       = DeviceType::normalizeProtocol(
            $data['protocol'] ?? $device->protocol
        );
        $device->icon_type      = DeviceType::iconKey(
            $device->type,
            $data['icon_type'] ?? $device->icon_type
        );

        if (! empty($data['router_version'])) {
            $device->router_version = $data['router_version'];
        }
        if (! empty($data['pop_name'])) {
            $device->pop_name = $data['pop_name'];
        }
        if (! empty($data['network_area_id'])) {
            $device->network_area_id = (int) $data['network_area_id'];
        }
        if (! empty($data['deskripsi'])) {
            $device->deskripsi = $data['deskripsi'];
        }
        if (! empty($data['penyebab'])) {
            $device->penyebab = $data['penyebab'];
        }
        if (! empty($data['gambar'])) {
            $device->gambar = $data['gambar'];
        }

        // Nilai default saat create.
        if (! $device->exists) {
            if (empty($device->status)) {
                $device->status = 'unknown';
            }
        }
    }
}
