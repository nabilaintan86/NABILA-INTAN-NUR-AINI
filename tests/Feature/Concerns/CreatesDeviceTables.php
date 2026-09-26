<?php

namespace Tests\Feature\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel minimum untuk menguji DeviceCsvService sebagai sumber data resmi.
 * Tabel `device` dibuat dari nol supaya status/latency bisa dikontrol test.
 */
trait CreatesDeviceTables
{
    protected function createDeviceTables(): void
    {
        foreach (['device', 'device_connections', 'monitoring_logs'] as $table) {
            Schema::dropIfExists($table);
        }

        // Dibutuhkan oleh header/offcanvas layout (jumlah notifikasi belum dibaca)
        if (! Schema::hasTable('device_notifications')) {
            Schema::create('device_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable();
                $table->string('device_name');
                $table->string('ip_address');
                $table->string('event_type');
                $table->string('event');
                $table->string('status');
                $table->text('message')->nullable();
                $table->integer('latency')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamp('checked_at');
                $table->timestamps();
            });
        }

        Schema::create('device', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('pop_name')->nullable();
            $table->string('type')->default('Server');
            $table->string('protocol')->default('ICMP');
            $table->string('status')->default('unknown');
            $table->float('avg_delay')->default(0);
            $table->float('responded_percentage')->default(0);
            $table->unsignedInteger('alerts_count')->default(0);
            $table->timestamps();
        });

        Schema::create('device_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from_device_id');
            $table->unsignedBigInteger('to_device_id');
            $table->string('connection_type')->default('lan');
            $table->timestamps();
        });

        Schema::create('monitoring_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable();
            $table->string('status')->default('unknown');
            $table->integer('latency')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });
    }
}
