<?php

namespace App\Services\SpeedTest;

use App\Models\Device;
use App\Services\Ssh\CommandResult;
use App\Services\Ssh\SshConnection;
use App\Services\Ssh\SshUnavailableException;

/**
 * Menjalankan perintah pengukuran di perangkat tujuan lewat SSH.
 *
 * Adapter tipis di atas SshConnection: seluruh pembangunan argumen ssh dan
 * seluruh aturan keamanannya hidup di satu kelas (App\Services\Ssh), supaya
 * tidak ada salinan yang bisa perlahan berbeda antar fitur.
 *
 * Tidak ada perintah yang bisa dipanggil dari luar — daftar perintah tetap
 * dikunci SpeedTestService, kelas ini hanya menjalankan.
 */
class SshTransport extends CommandTransport
{
    private function __construct(
        private readonly SshConnection $connection,
    ) {
    }

    /**
     * @throws SshUnavailableException
     */
    public static function forDevice(Device $device): self
    {
        return new self(SshConnection::forDevice($device));
    }

    /**
     * Ke alamat yang diketik Super Admin sendiri, bukan dari daftar perangkat.
     *
     * @throws SshUnavailableException
     */
    public static function forManualHost(string $host): self
    {
        return new self(SshConnection::forManualHost($host));
    }

    /**
     * {@inheritDoc}
     */
    public function run(array $command, ?string $input = null, int $timeout = 60): CommandResult
    {
        return $this->connection->run($command, $input, $timeout);
    }

    /**
     * {@inheritDoc}
     */
    public function isAvailable(string $binary): bool
    {
        return $this->connection->commandExists($binary);
    }

    /**
     * {@inheritDoc}
     */
    public function source(): string
    {
        return 'perangkat';
    }
}
