<?php

namespace Tests\Feature\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

trait CreatesDhcpTables
{
    protected function createDhcpTables(): void
    {
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

        if (! Schema::hasTable('dhcp_leases')) {
            Schema::create('dhcp_leases', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('device_id')->nullable()->index();
                $table->string('mac_address')->nullable();
                $table->string('ip_address');
                $table->string('hostname')->nullable();
                $table->string('client_id')->nullable();
                $table->string('vendor')->nullable();
                $table->string('router')->nullable();
                $table->string('location')->nullable();
                $table->timestamp('lease_start')->nullable();
                $table->timestamp('lease_end')->nullable();
                $table->enum('classification', ['known', 'new', 'unknown'])->default('unknown');
                $table->timestamp('first_seen')->nullable();
                $table->timestamp('last_seen')->nullable();
                $table->string('source')->nullable();
                $table->text('comment')->nullable();
                $table->timestamps();
                $table->unique(['mac_address', 'ip_address']);
            });
        } else {
            if (! Schema::hasColumn('dhcp_leases', 'device_id')) {
                Schema::table('dhcp_leases', function (Blueprint $table) {
                    $table->unsignedBigInteger('device_id')->nullable()->index();
                    $table->text('comment')->nullable();
                });
            }

            if (! Schema::hasColumn('dhcp_leases', 'client_id')) {
                Schema::table('dhcp_leases', function (Blueprint $table) {
                    $table->string('client_id')->nullable()->index()->after('hostname');
                });
            }
        }

        // Tabel known device (yang dicari DhcpRecognition).
        if (! Schema::hasTable('devices')) {
            Schema::create('devices', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('ip_address')->nullable();
                $table->string('pop_name')->nullable();
                $table->timestamps();
            });
        } elseif (! Schema::hasColumn('devices', 'pop_name')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->string('pop_name')->nullable();
            });
        }
    }
}
