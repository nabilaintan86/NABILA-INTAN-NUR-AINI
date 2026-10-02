<?php

namespace App\Services\Ssh;

use RuntimeException;

/**
 * Perintah ke perangkat luar tidak bisa dijalankan karena konfigurasi SSH di
 * server belum lengkap (fitur dimatikan, user kosong, private key tidak
 * terbaca, dan sejenisnya).
 *
 * Pesannya sengaja aman ditampilkan ke layar: hanya menyebut nama variabel
 * konfigurasi, tidak pernah memuat path atau isi kunci.
 */
class SshUnavailableException extends RuntimeException
{
}
