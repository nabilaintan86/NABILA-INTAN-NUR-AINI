<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_connections', function (Blueprint $table) {
            if (! Schema::hasColumn('device_connections', 'direction')) {
                $table->string('direction', 32)->default('forward')->after('connection_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('device_connections', function (Blueprint $table) {
            if (Schema::hasColumn('device_connections', 'direction')) {
                $table->dropColumn('direction');
            }
        });
    }
};
