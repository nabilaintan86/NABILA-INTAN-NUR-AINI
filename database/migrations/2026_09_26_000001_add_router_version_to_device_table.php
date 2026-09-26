<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('device', 'router_version')) {
            Schema::table('device', function (Blueprint $table) {
                $table->string('router_version')->nullable()->after('icon_type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('device', 'router_version')) {
            Schema::table('device', function (Blueprint $table) {
                $table->dropColumn('router_version');
            });
        }
    }
};
