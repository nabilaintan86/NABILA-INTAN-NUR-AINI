<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ezyhome_devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address')->nullable();
            $table->string('hostname')->nullable();
            $table->string('type')->default('Server');
            $table->string('protocol', 32)->default('ICMP');
            $table->text('deskripsi')->nullable();
            $table->text('penyebab')->nullable();
            $table->string('gambar')->nullable();
            $table->string('status')->default('unknown');
            $table->string('source', 10)->default('db');
            $table->string('icon_type')->nullable();
            $table->string('router_version')->nullable();
            $table->string('pop_name')->nullable();
            $table->unsignedBigInteger('polls')->default(0);
            $table->decimal('responded_percentage', 6, 2)->default(0);
            $table->decimal('missed_percentage', 6, 2)->default(0);
            $table->string('down_time')->nullable();
            $table->string('period')->nullable();
            $table->unsignedInteger('alerts_count')->default(0);
            $table->integer('avg_delay')->default(0);
            $table->integer('min_delay')->default(0);
            $table->integer('max_delay')->default(0);
            $table->string('uptime')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ezyhome_devices');
    }
};