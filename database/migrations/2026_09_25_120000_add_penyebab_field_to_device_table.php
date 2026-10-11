<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom "penyebab masalah" manual per device, diisi admin lewat halaman
     * detail device dan ditampilkan di panel Perangkat Paling Bermasalah.
     */
    public function up(): void
    {
        if (Schema::hasTable('devices') && ! Schema::hasColumn('devices', 'penyebab')) {
            Schema::table('devices', function (Blueprint $table): void {
                $table->text('penyebab')->nullable();
            });
        }
    }

    public function down(): void
    {
    }
};