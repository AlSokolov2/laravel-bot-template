<?php

namespace App\Http\Controllers;

use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Telegram\Bot\Api;

class TelegramWebhookController extends Controller
{
    /**
     * Handle incoming webhook POST from Telegram.
     *
     * URL:  POST  /webhook/{bot}
     * CSRF: excluded in bootstrap/app.php
     */
    public function __invoke(Request $request, string $bot = 'mybot', TelegramService $service): Response
    {
        // The SDK resolves the bot by config key.  'mybot' is the default.
        $update = app(Api::class)->bot($bot)->commandsHandler(true);

        if ($update) {
            $service->handleUpdate($update);
        }

        // Always return 200 to prevent Telegram from re-sending.
        return response()->noContent();
    }
}
