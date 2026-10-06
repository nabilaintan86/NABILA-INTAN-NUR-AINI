<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topology_map_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topology_map_id')->constrained('topology_maps')->cascadeOnDelete();
            $table->foreignId('topology_map_area_id')->nullable()->constrained('topology_map_areas')->nullOnDelete();
            $table->foreignId('device_id')->constrained('devices')->cascadeOnDelete();
            $table->integer('pos_x')->nullable();
            $table->integer('pos_y')->nullable();
            $table->string('icon_type')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            // Aturan: 1 device cuma boleh di 1 map
            $table->unique('device_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topology_map_devices');
    }
};