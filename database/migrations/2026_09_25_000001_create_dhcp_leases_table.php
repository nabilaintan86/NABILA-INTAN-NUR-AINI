<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dhcp_leases', function (Blueprint $table) {
            $table->id();

            $table->string('mac_address')->nullable()->index();
            $table->string('ip_address')->index();
            $table->string('hostname')->nullable();
            $table->string('vendor')->nullable();
            // DHCP server / router yang memberikan lease
            $table->string('router')->nullable();
            // Router/Lokasi hasil identifikasi dari known device (tabel device: pop_name)
            $table->string('location')->nullable()->index();

            $table->timestamp('lease_start')->nullable();
            $table->timestamp('lease_end')->nullable();

            // known  = dikenal di tabel device (devices.csv)
            // new    = belum dikenal tetapi punya identitas (hostname/vendor) atau baru terlihat
            // unknown = tidak dikenal dan tanpa identitas
            $table->enum('classification', ['known', 'new', 'unknown'])
                ->default('unknown')
                ->index();

            $table->timestamp('first_seen')->nullable();
            $table->timestamp('last_seen')->nullable();

            // Sumber data (nama file export DHCP server / identitas router)
            $table->string('source')->nullable();

            $table->timestamps();

            $table->unique(['mac_address', 'ip_address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dhcp_leases');
    }
};