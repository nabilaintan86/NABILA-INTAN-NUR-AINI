<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign('activity_logs_device_id_foreign');
            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropForeign('activity_logs_device_id_foreign');
            $table->foreign('device_id')
                ->references('id')
                ->on('devices')
                ->nullOnDelete();
        });
    }
};
