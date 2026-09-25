<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device', function (Blueprint $table) {
            $table->string('icon_type')->nullable()->after('status');
            $table->string('pop_name')->nullable()->after('icon_type');
        });
    }

    public function down(): void
    {
        Schema::table('device', function (Blueprint $table) {
            $table->dropColumn(['icon_type', 'pop_name']);
        });
    }
};
