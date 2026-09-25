<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained('device')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->dateTime('tanggal')->index();
            $table->text('catatan');
            $table->string('priority', 20)->default('Normal')->index();
            $table->timestamps();

            $table->index(['device_id', 'tanggal']);
            $table->index(['user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_notes');
    }
};
