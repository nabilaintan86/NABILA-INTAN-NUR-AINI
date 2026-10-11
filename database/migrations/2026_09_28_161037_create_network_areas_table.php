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
        Schema::create('network_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->integer('pos_x')->default(20);
            $table->integer('pos_y')->default(20);
            $table->integer('width')->default(280);
            $table->integer('height')->default(220);
            $table->string('border_color', 20)->default('#1a8024');
            $table->string('bg_color', 20)->default('#f8fafc');
            $table->string('title_color', 20)->default('#1a8024');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        if (Schema::hasTable('devices') && !Schema::hasColumn('devices', 'network_area_id')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->foreignId('network_area_id')->nullable()->after('pop_name')->constrained('network_areas')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('devices') && Schema::hasColumn('devices', 'network_area_id')) {
            Schema::table('devices', function (Blueprint $table) {
                $table->dropForeign(['network_area_id']);
                $table->dropColumn('network_area_id');
            });
        }
        Schema::dropIfExists('network_areas');
    }
};
