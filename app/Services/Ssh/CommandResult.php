<?php

namespace App\Services\Ssh;

/**
 * Hasil satu perintah: berhasil atau tidak, plus isinya.
 *
 * Sengaja tidak menyimpan exit code atau stderr supaya detail internal
 * server (path binary, pesan error ssh) tidak bocor ke layar. Kalau butuh
 * diagnosa, detail itu ada di log Laravel.
 */
final readonly class CommandResult
{
    public function __construct(
        public bool $ok,
        public string $output,
    ) {
    }

    public static function failed(): self
    {
        return new self(false, '');
    }
}
