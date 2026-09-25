<?php

namespace App\Http\Controllers;

use App\Services\TelegramUpdateHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

/**
 * LEGACY: endpoint webhook Telegram.
 *
 * Sistem aktif sekarang memakai long polling (`php artisan telegram:poll`),
 * endpoint ini sebenarnya tidak lagi dipakai di workflow normal.
 * Route/controller dibiarkan agar tidak mengganggu project lain, dan
 * logika /start tetap bekerja via TelegramUpdateHandler yang sama.
 */
class TelegramWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $secret = (string) config('services.telegram.webhook_secret');
        $provided = (string) $request->header('X-Telegram-Bot-Api-Secret-Token', '');
        $managedMode = Schema::hasTable('telegram_users');

        if (($managedMode || app()->environment('production')) && $secret === '') {
            return response()->json(['ok' => false, 'message' => 'Webhook secret belum dikonfigurasi.'], 503);
        }

        if ($secret !== '' && ! hash_equals($secret, $provided)) {
            return response()->json(['ok' => false], 403);
        }

        $result = app(TelegramUpdateHandler::class)->handleUpdate($request->json()->all());

        if ($result === false) {
            return response()->json(['ok' => false, 'message' => 'Rate limit tercapai.'], 429);
        }

        return response()->json(['ok' => true]);
    }
}
