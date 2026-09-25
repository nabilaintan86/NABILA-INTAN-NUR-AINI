<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable()->index();
            $table->string('device_name');
            $table->string('ip_address');
            $table->string('status'); // up | down | warning
            $table->string('reason')->nullable();
            $table->integer('latency')->nullable();
            $table->timestamp('log_time')->index();
            $table->timestamps();

            $table->foreign('device_id')
                ->references('id')
                ->on('device')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};