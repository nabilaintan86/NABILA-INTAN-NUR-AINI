<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_device_id')->references('id')->on('devices')->cascadeOnDelete();
            $table->foreignId('to_device_id')->references('id')->on('devices')->cascadeOnDelete();
            $table->string('connection_type')->default('ethernet');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_connections');
    }
};
