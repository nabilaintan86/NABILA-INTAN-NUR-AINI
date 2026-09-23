<?php

namespace App\Http\Controllers;

use App\Services\TelegramUpdateHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $secret = config('services.telegram.webhook_secret');

        if (!empty($secret)
            && !hash_equals($secret, (string) $request->header('X-Telegram-Bot-Api-Secret-Token', ''))) {
            return response()->json(['ok' => false], 403);
        }

        app(TelegramUpdateHandler::class)->handleUpdate($request->json()->all());

        return response()->json(['ok' => true]);
    }
}