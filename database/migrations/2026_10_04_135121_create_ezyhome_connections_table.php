<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ezyhome_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_device_id')
                ->constrained('ezyhome_devices')
                ->cascadeOnDelete();
            $table->foreignId('from_position_id')
                ->nullable()
                ->constrained('ezyhome_positions')
                ->cascadeOnDelete();
            $table->foreignId('to_device_id')
                ->constrained('ezyhome_devices')
                ->cascadeOnDelete();
            $table->foreignId('to_position_id')
                ->nullable()
                ->constrained('ezyhome_positions')
                ->cascadeOnDelete();
            $table->string('connection_type')->default('ethernet');
            $table->string('direction', 32)->default('forward');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ezyhome_connections');
    }
};