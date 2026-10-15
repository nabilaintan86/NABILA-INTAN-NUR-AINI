<?php

namespace App\Services\SpeedTest;

use App\Services\Ssh\CommandResult;

/**
 * Standards cara sebuah perintah pengukuran dijalankan: di server NOC
 * (LocalTransport) atau di perangkat tujuan lewat SSH (SshTransport).
 *
 * SpeedTestService hanya mengetahui cara pengukurannya; tempat eksekusinya
 * dikendalikan kelas ini. Kontrak ini juga yang menjaga satu aturan penting:
 * perintah masuk sebagai ARRAY dan tidak pernah sebagai string yang dirangkai
 * bebas, sehingga tidak ada input pengguna yang bisa ikut masuk ke command line.
 */
abstract class CommandTransport
{
    /**
     * Jalankan satu perintah dan kembalikan hasilnya.
     *
     * @param  list<string>  $command
     */
    abstract public function run(array $command, ?string $input = null, int $timeout = 60): CommandResult;

    /**
     * Apakah sebuah biner tersedia di tempat perintah ini dijalankan?
     */
    abstract public function isAvailable(string $binary): bool;

    /**
     * Keterangan singkat tentang tempat pengujian, dipakai di pesan status
     * supaya pengguna tahu angka ini berasal dari server atau dari router.
     */
    abstract public function source(): string;
}
