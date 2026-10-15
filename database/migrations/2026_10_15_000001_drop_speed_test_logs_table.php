<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Fitur Speed Test sudah dihapus dari codebase (controller, service, model,
 * view, dan migrasi pembuatnya). Migrasi ini merapikan database yang sudah
 * terlanjur memiliki tabel `speed_test_logs` agar tidak menyisakan tabel mati.
 *
 * Aman dijalankan di database baru: dropIfExists tidak melakukan apa pun bila
 * tabelnya belum pernah dibuat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('speed_test_logs');
    }

    public function down(): void
    {
        // Sengaja kosong: tabel pembuatnya sudah dihapus bersama fitur Speed Test.
    }
};
