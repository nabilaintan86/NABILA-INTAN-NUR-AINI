<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id')->nullable()->index();
            $table->string('device_name');
            $table->string('ip_address');
            $table->string('event_type'); // up->down | down->up
            $table->string('event'); // DOWN | BACK ONLINE
            $table->string('status'); // down | online
            $table->text('message')->nullable();
            $table->integer('latency')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamp('checked_at')->index();
            $table->timestamps();

            $table->foreign('device_id')
                ->references('id')
                ->on('device')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_notifications');
    }
};