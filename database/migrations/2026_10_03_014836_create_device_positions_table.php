<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel device_positions: 1 device bisa punya banyak posisi di map.
        Schema::create('device_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->foreignId('network_area_id')->nullable()->constrained('network_areas')->nullOnDelete();
            $table->integer('pos_x')->nullable();
            $table->integer('pos_y')->nullable();
            $table->timestamps();

            $table->index(['device_id', 'network_area_id']);
        });

        // 2. Copy posisi existing dari tabel devices ke device_positions.
        //    Setiap device yang sudah punya posisi (map_positioned=1) dikopi.
        $devices = DB::table('devices')
            ->where('map_positioned', true)
            ->whereNotNull('pos_x')
            ->whereNotNull('pos_y')
            ->get();

        $now = now();

        foreach ($devices as $d) {
            DB::table('device_positions')->insert([
                'device_id'        => $d->id,
                'network_area_id'  => $d->network_area_id,
                'pos_x'            => $d->pos_x,
                'pos_y'            => $d->pos_y,
                'created_at'       => $now,
                'updated_at'       => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('device_positions');
    }
};