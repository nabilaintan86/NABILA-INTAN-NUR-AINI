<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration `2026_09_30_100000_add_target_device_to_speed_test_logs_table`
 * sudah pernah dijalankan di database sebelum kolom target_type dan target_host
 * ikut ditambahkan ke file tersebut. Karena Laravel menandai nama migration
 * sebagai selesai, penambahan kolom itu tidak pernah dieksekusi, sehingga
 * penyimpanan log gagal dengan:
 *
 *   SQLSTATE[42S22]: Column not found: 1054 Unknown column 'target_type'
 *
 * Migration ini menutup celah tersebut secara idempotent: hanya menambahkan
 * kolom yang benar-benar belum ada.
 *
 * 'local'  = diuji dari server NOC (bukan lewat perangkat terdaftar)
 * 'device' = target dari daftar perangkat (device_id terisi)
 * 'manual' = alamat yang diketik Super Admin sendiri
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('speed_test_logs', 'target_type')
            && Schema::hasColumn('speed_test_logs', 'target_host')) {
            return;
        }

        Schema::table('speed_test_logs', function (Blueprint $table): void {
            if (! Schema::hasColumn('speed_test_logs', 'target_type')) {
                $table->string('target_type', 16)->default('local')->after('device_ip_address');
            }

            // 253 karakter = panjang maksimum nama host yang valid.
            if (! Schema::hasColumn('speed_test_logs', 'target_host')) {
                $table->string('target_host', 253)->nullable()->after('target_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('speed_test_logs', function (Blueprint $table): void {
            $columns = array_values(array_filter(
                ['target_type', 'target_host'],
                fn (string $column): bool => Schema::hasColumn('speed_test_logs', $column),
            ));

            if ($columns === []) {
                return;
            }

            $table->dropColumn($columns);
        });
    }
};