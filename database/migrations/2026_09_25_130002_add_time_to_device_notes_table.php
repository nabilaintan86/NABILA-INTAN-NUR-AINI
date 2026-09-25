<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('device_notes') && Schema::getColumnType('device_notes', 'tanggal') === 'date') {
            Schema::table('device_notes', function (Blueprint $table): void {
                $table->dateTime('tanggal')->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('device_notes') && Schema::getColumnType('device_notes', 'tanggal') === 'datetime') {
            Schema::table('device_notes', function (Blueprint $table): void {
                $table->date('tanggal')->change();
            });
        }
    }
};
