<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom ini sudah ikut dibuat oleh 2026_09_10_122528_create_device_table,
        // jadi dilewati bila sudah ada (pola yang dipakai migration lain).
        $missing = array_values(array_filter(
            ['icon_type', 'pop_name'],
            fn (string $column): bool => ! Schema::hasColumn('device', $column)
        ));

        if ($missing === []) {
            return;
        }

        Schema::table('device', function (Blueprint $table) use ($missing) {
            foreach ($missing as $column) {
                $table->string($column)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('device', function (Blueprint $table) {
            $table->dropColumn(['icon_type', 'pop_name']);
        });
    }
};
