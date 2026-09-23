<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Catatan: tabel device memakai nama 'device' (lihat Device::$table),
     * bukan 'devices'. Kolom pos_x & pos_y sudah ditambahkan migrasi
     * 2026_09_14_100001_add_network_map_fields_to_device_table, jadi
     * dijaga dengan hasColumn agar aman dijalankan berulang.
     */
    public function up(): void
    {
        Schema::table('device', function (Blueprint $table) {
            if (!Schema::hasColumn('device', 'pos_x')) {
                $table->integer('pos_x')->nullable()->after('pop_name');
            }
            if (!Schema::hasColumn('device', 'pos_y')) {
                $table->integer('pos_y')->nullable()->after('pos_x');
            }
            if (!Schema::hasColumn('device', 'map_positioned')) {
                $table->boolean('map_positioned')->default(false)->after('pos_y');
            }
        });
    }

    public function down(): void
    {
        Schema::table('device', function (Blueprint $table) {
            if (Schema::hasColumn('device', 'map_positioned')) {
                $table->dropColumn('map_positioned');
            }
        });
    }
};
