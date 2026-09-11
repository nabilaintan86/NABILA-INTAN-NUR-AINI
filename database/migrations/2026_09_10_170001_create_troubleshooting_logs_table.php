<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('troubleshooting_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable()->index();
            $table->string('device_name');
            $table->string('ip_address');
            $table->string('status')->default('warning'); // up | down | warning
            $table->unsignedInteger('latency')->nullable();
            $table->string('diagnostic')->default('ICMP Ping');
            $table->string('action')->default('Re-Test Connection');
            $table->string('error')->nullable();
            $table->text('output')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamp('checked_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('troubleshooting_logs');
    }
};