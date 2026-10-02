<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Speed Test jadi bisa diarahkan ke salah satu perangkat dari tabel `device`,
 * atau ke alamat yang diketik Super Admin sendiri, jadi riwayat perlu tahu
 * target mana yang diuji.
 *
 * Nama dan IP disimpan sebagai salinan (snapshot), bukan hanya foreign key:
 * log pengujian adalah bukti historis, dan perangkat bisa saja diubah atau
 * dihapus dari daftar tanpa mengubah kenyataan bandwidth saat itu diukur.
 *
 * Kolom device_* hanya dipakai untuk target dari daftar. Target manual disimpan
 * terpisah di target_host, karena isinya bisa berupa nama host (sampai 253
 * karakter) dan tidak selalu berupa IP perangkat terdaftar.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('speed_test_logs', function (Blueprint $table): void {
            if (! Schema::hasColumn('speed_test_logs', 'device_id')) {
                // nullOnDelete: log tetap ada walau device dihapus dari daftar.
                $table->foreignId('device_id')
                    ->nullable()
                    ->after('user_id')
                    ->constrained('device')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('speed_test_logs', 'device_name')) {
                $table->string('device_name', 191)->nullable()->after('device_id');
            }

            if (! Schema::hasColumn('speed_test_logs', 'device_ip_address')) {
                $table->string('device_ip_address', 45)->nullable()->after('device_name');
            }

            // 'local' = server NOC, 'device' = dari daftar, 'manual' = diketik.
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
                ['device_id', 'device_name', 'device_ip_address', 'target_type', 'target_host'],
                fn (string $column): bool => Schema::hasColumn('speed_test_logs', $column),
            ));

            if ($columns === []) {
                return;
            }

            if (in_array('device_id', $columns, true)) {
                $table->dropForeign(['device_id']);
            }

            $table->dropColumn($columns);
        });
    }
};
