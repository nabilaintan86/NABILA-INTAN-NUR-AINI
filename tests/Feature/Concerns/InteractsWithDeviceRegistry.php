<?php

namespace Tests\Feature\Concerns;

use App\Services\DeviceCsvService;
use Tests\Support\DeviceTableAsRegistry;

/**
 * Helper untuk test yang menyangkut perangkat resmi.
 *
 * Karena `devices.csv` adalah sumber daftar resmi, test tidak bisa mengandalkan
 * file CSV proyek yang berisi IP asli. Test mendaftarkan IP yang dikehendaki
 * ke CSV sementara, lalu singleton DeviceCsvService diarahkan ke sana.
 */
trait InteractsWithDeviceRegistry
{
    /** @var string|null */
    protected $registryPath = null;

    /** @var array<string, string> */
    protected array $registryRows = [];

    /**
     * Arahkan DeviceCsvService ke devices.csv sementara berisi IP yang diberikan.
     *
     * @param  string[]|string  $ips  satu IP, atau daftar IP
     * @return string  isi devices.csv yang dipakai
     */
    protected function registerOfficialDevices(string|array $ips, string $status = 'up'): string
    {
        foreach (is_array($ips) ? $ips : [$ips] as $index => $ip) {
            $this->registerOfficialDevice('Official '.$index, $ip, $status);
        }

        return $this->registryCsvContents();
    }

    /**
     * Tambah satu baris devices.csv sementara.
     */
    protected function registerOfficialDevice(string $name, string $ip, string $status = 'up'): string
    {
        $this->registryRows[$name.'|'.$ip] = $name.','.$ip.',ICMP,,,,'.$status;

        return $this->writeRegistryCsv();
    }

    protected function registerOfficialDevicesByIp(array $ipToStatus): string
    {
        foreach ($ipToStatus as $ip => $status) {
            $this->registerOfficialDevice('Device '.$ip, (string) $ip, (string) $status);
        }

        return $this->writeRegistryCsv();
    }

    /**
     * Isi registry kosong (tidak ada perangkat resmi sama sekali).
     */
    protected function registerNoOfficialDevices(): string
    {
        $this->registryRows = [];

        return $this->writeRegistryCsv();
    }

    protected function registryCsvContents(): string
    {
        $lines = ['NAMA,IP ADD,TYPE,DESKRIPSI,GAMBAR,STATUS'];

        foreach ($this->registryRows as $row) {
            $lines[] = $row;
        }

        return implode("\n", $lines)."\n";
    }

    protected function writeRegistryCsv(): string
    {
        $contents = $this->registryCsvContents();

        if ($this->registryPath === null) {
            $this->registryPath = tempnam(sys_get_temp_dir(), 'registry-').'.csv';
            $this->beforeApplicationDestroyed(function () {
                if ($this->registryPath !== null && is_file($this->registryPath)) {
                    @unlink($this->registryPath);
                }
            });
        }

        file_put_contents($this->registryPath, $contents);

        $this->app->singleton(DeviceCsvService::class, fn () => new DeviceCsvService()->usePath($this->registryPath));

        return $contents;
    }

    /**
     * Untuk test yang bukan menguji DeviceCsvService: perlakukan seluruh isi
     * tabel `device` sebagai daftar resmi. Lihat DeviceTableAsRegistry.
     */
    protected function treatDeviceTableAsOfficialRegistry(): void
    {
        $this->app->singleton(DeviceCsvService::class, fn () => new DeviceTableAsRegistry);
    }
}
