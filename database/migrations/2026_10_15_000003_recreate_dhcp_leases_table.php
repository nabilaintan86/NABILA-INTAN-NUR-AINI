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

            $table->unsignedBigInteger('device_id')->nullable()->index();
            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->nullOnDelete();

            $table->string('mac_address')->nullable()->index();
            $table->string('ip_address')->index();
            $table->string('hostname')->nullable();
            $table->string('client_id')->nullable()->index();
            $table->string('vendor')->nullable();
            $table->string('router')->nullable();
            $table->string('location')->nullable()->index();

            $table->timestamp('lease_start')->nullable();
            $table->timestamp('lease_end')->nullable();

            $table->enum('classification', ['known', 'new', 'unknown'])
                ->default('unknown')
                ->index();

            $table->timestamp('first_seen')->nullable();
            $table->timestamp('last_seen')->nullable();

            $table->string('source')->nullable();
            $table->text('comment')->nullable();

            $table->timestamps();

            $table->unique(['mac_address', 'ip_address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dhcp_leases');
    }
};
