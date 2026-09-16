<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign('activity_logs_device_id_foreign');
            $table->foreign('device_id')
                ->references('id')
                ->on('device')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign('activity_logs_device_id_foreign');
            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->nullOnDelete();
        });
    }
};
