<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ezyhome_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ezyhome_device_id')
                ->constrained('ezyhome_devices')
                ->cascadeOnDelete();
            $table->foreignId('ezyhome_area_id')
                ->nullable()
                ->constrained('ezyhome_areas')
                ->nullOnDelete();
            $table->integer('pos_x')->nullable();
            $table->integer('pos_y')->nullable();
            $table->timestamps();
            $table->index(['ezyhome_device_id', 'ezyhome_area_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ezyhome_positions');
    }
};