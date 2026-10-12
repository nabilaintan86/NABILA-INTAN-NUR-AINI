<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('topology_map_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topology_map_id')->constrained('topology_maps')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->integer('pos_x')->default(20);
            $table->integer('pos_y')->default(20);
            $table->integer('width')->default(240);
            $table->integer('height')->default(200);
            $table->string('border_color', 20)->default('#4F46E5');
            $table->string('bg_color', 20)->default('#f8fafc');
            $table->string('title_color', 20)->default('#4F46E5');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topology_map_areas');
    }
};