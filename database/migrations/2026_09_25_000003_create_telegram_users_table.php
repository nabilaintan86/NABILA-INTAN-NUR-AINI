<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_users', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id', 64)->unique();
            $table->string('username')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->enum('role', ['super_admin', 'admin', 'staff', 'pending'])->default('pending');
            $table->boolean('is_approved')->default(false)->index();
            $table->timestamp('last_active_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_users');
    }
};
