<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('ICMP');
            $table->string('ip_address');
            $table->bigInteger('polls')->default(0);
            $table->decimal('responded_percentage', 6, 2)->default(100);
            $table->decimal('missed_percentage', 6, 2)->default(0);
            $table->string('down_time')->nullable();
            $table->string('period')->nullable();
            $table->integer('alerts_count')->default(0);
            $table->integer('avg_delay')->default(0);
            $table->integer('min_delay')->default(0);
            $table->integer('max_delay')->default(0);
            $table->string('status')->default('up');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
