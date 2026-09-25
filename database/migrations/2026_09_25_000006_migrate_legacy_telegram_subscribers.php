<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('telegram_subscribers') || ! Schema::hasTable('telegram_users')) {
            return;
        }

        DB::table('telegram_subscribers')
            ->where('is_active', true)
            ->orderBy('id')
            ->each(function ($subscriber): void {
                DB::table('telegram_users')->updateOrInsert(
                    ['chat_id' => (string) $subscriber->chat_id],
                    [
                        'username' => $subscriber->username,
                        'first_name' => $subscriber->first_name,
                        'last_name' => $subscriber->last_name,
                        'role' => 'staff',
                        'is_approved' => true,
                        'last_active_at' => $subscriber->last_interacted_at ?: now(),
                        'notes' => 'Migrated dari telegram_subscribers.',
                        'created_at' => $subscriber->created_at ?: now(),
                        'updated_at' => now(),
                    ]
                );
            });
    }

    public function down(): void {}
};
