<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('dhcp_leases', 'client_id')) {
            Schema::table('dhcp_leases', function (Blueprint $table) {
                $table->string('client_id')->nullable()->index()->after('hostname');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('dhcp_leases', 'client_id')) {
            Schema::table('dhcp_leases', function (Blueprint $table) {
                $table->dropColumn('client_id');
            });
        }
    }
};
