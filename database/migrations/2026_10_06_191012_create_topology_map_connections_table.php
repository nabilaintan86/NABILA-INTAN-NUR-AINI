<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topology_map_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topology_map_id')->constrained('topology_maps')->cascadeOnDelete();
            $table->foreignId('from_map_device_id')->nullable()->constrained('topology_map_devices')->cascadeOnDelete();
            $table->foreignId('from_map_area_id')->nullable()->constrained('topology_map_areas')->cascadeOnDelete();
            $table->foreignId('to_map_device_id')->nullable()->constrained('topology_map_devices')->cascadeOnDelete();
            $table->foreignId('to_map_area_id')->nullable()->constrained('topology_map_areas')->cascadeOnDelete();
            $table->string('connection_type')->default('ethernet');
            $table->string('direction', 32)->default('forward');
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topology_map_connections');
    }
};