<?php

namespace Tests\Feature\Concerns;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel pendukung yang dibaca halaman Monitoring, tapi tidak dibuat manual
 * oleh test yang memakai CreatesDeviceTables.
 *
 * `network_areas` + `device.network_area_id` dipakai
 * MonitoringController::index() lewat NetworkArea::withCount('devices'),
 * dan `map_settings` dipakai NetworkTopologyService untuk menyimpan tata
 * letak peta. Sebelum keduanya ada, setiap halaman Monitoring berakhir 500
 * dengan "no such table" — bukan karena kodenya salah, tapi karena skema
 * test-nya belum menyusul.
 *
 * Bentuk kolomnya disalin dari migration aslinya
 * (2026_09_28_161037_create_network_areas_table dan
 * 2026_09_28_152237_create_map_settings_table) supaya test dan produksi
 * tidak diam-diam berbeda.
 */
trait CreatesNetworkAreaTables
{
    protected function createNetworkAreaTables(): void
    {
        if (! Schema::hasTable('network_areas')) {
            Schema::create('network_areas', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('code')->nullable();
                $table->text('description')->nullable();
                $table->integer('pos_x')->default(20);
                $table->integer('pos_y')->default(20);
                $table->integer('width')->default(280);
                $table->integer('height')->default(220);
                $table->string('border_color', 20)->default('#1a8024');
                $table->string('bg_color', 20)->default('#f8fafc');
                $table->string('title_color', 20)->default('#1a8024');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (Schema::hasTable('device') && ! Schema::hasColumn('device', 'network_area_id')) {
            Schema::table('device', function (Blueprint $table) {
                $table->unsignedBigInteger('network_area_id')->nullable();
            });
        }

        if (! Schema::hasTable('map_settings')) {
            Schema::create('map_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique()->index();
                $table->longText('value')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->timestamps();
            });
        }
    }
}
