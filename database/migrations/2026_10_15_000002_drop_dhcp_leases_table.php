<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Fitur DHCP Lease sudah dihapus dari codebase (controller, service, model,
 * command, view, dan migrasi pembuatnya). Migrasi ini merapikan database yang
 * sudah terlanjur memiliki tabel `dhcp_leases` agar tidak menyisakan tabel mati.
 *
 * Aman dijalankan di database baru: dropIfExists tidak melakukan apa pun bila
 * tabelnya belum pernah dibuat.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('dhcp_leases');
    }

    public function down(): void
    {
        // Sengaja kosong: tabel pembuatnya sudah dihapus bersama fitur DHCP Lease.
    }
};
