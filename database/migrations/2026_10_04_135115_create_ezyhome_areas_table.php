<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ezyhome_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->integer('pos_x')->default(20);
            $table->integer('pos_y')->default(20);
            $table->integer('width')->default(240);
            $table->integer('height')->default(200);
            $table->string('border_color', 20)->default('#1a8024');
            $table->string('bg_color', 20)->default('#f8fafc');
            $table->string('title_color', 20)->default('#1a8024');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ezyhome_areas');
    }
};