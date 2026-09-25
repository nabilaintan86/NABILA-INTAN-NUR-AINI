<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dhcp_leases', function (Blueprint $table) {
            // Relasi opsional ke device resmi di tabel `device`.
            // Tidak ada duplikasi data device — hanya referensi.
            $table->unsignedBigInteger('device_id')->nullable()->after('id')->index();
            $table->foreign('device_id')
                ->references('id')
                ->on('device')
                ->nullOnDelete();

            $table->text('comment')->nullable()->after('source');
        });
    }

    public function down(): void
    {
        Schema::table('dhcp_leases', function (Blueprint $table) {
            $table->dropForeign(['device_id']);
            $table->dropColumn(['device_id', 'comment']);
        });
    }
};