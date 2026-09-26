<?php

namespace Tests\Support;

use App\Models\Device;
use App\Services\DeviceCsvService;
use Illuminate\Support\Collection;

/**
 * Test double: perlakukan seluruh isi tabel `device` sebagai daftar resmi.
 *
 * Hanya untuk test yang tujuan utamanya bukan menguji DeviceCsvService —
 * misalnya test notifikasi Telegram, di mana devices.csv proyek berisi IP
 * asli sehingga tidak bisa dipakai. Test yang memang menguji sumber data
 * resmi harus memakai devices.csv sementara, bukan class ini.
 */
class DeviceTableAsRegistry extends DeviceCsvService
{
    public function officialDevices(): Collection
    {
        return $this->allDevices();
    }

    public function officialDevicesUnique(): Collection
    {
        return $this->uniqueDevicesOf($this->allDevices());
    }

    /**
     * Sengaja tanpa join device_connections supaya test tidak perlu membuat
     * tabel topologi hanya untuk memverifikasi daftar perangkat.
     */
    protected function allDevices(): Collection
    {
        return Device::query()->orderBy('id')->get();
    }

    public function exists(): bool
    {
        return true;
    }

    public function path(): string
    {
        return 'memory://device-table-as-registry';
    }

    public function readRows(): array
    {
        return $this->allDevices()
            ->map(fn (Device $d) => [
                'name' => (string) $d->name,
                'ip' => (string) $d->ip_address,
                'type' => (string) ($d->protocol ?: 'ICMP'),
                'description' => '',
                'image' => '',
                'status' => (string) $d->status,
            ])
            ->all();
    }
}
