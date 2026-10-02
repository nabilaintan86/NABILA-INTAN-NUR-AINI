<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Riwayat hasil speed test. Halaman dan endpoint-nya hanya bisa diakses
     * Super Admin, jadi tabel ini ikut serves sebagai audit log fitur tersebut.
     */
    public function up(): void
    {
        Schema::create('speed_test_logs', function (Blueprint $table) {
            $table->id();

            // Siapa yang menjalankan. nullOnDelete supaya log tetap tersimpan
            // walaupun akunnya dihapus.
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->decimal('download_mbps', 10, 2)->nullable();
            $table->decimal('upload_mbps', 10, 2)->nullable();
            $table->decimal('ping_ms', 8, 2)->nullable();

            // 'cli' = speedtest-cli, 'curl' = pengukuran cadangan.
            $table->string('source', 16)->default('curl');
            $table->string('server', 191)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->unsignedInteger('duration_ms')->nullable();

            // 'ok' atau 'gagal' + alasan singkatnya.
            $table->string('status', 16)->default('ok');
            $table->string('message', 255)->nullable();

            $table->timestamp('tested_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('speed_test_logs');
    }
};
