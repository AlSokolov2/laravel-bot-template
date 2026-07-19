<?php

use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Telegram Bot webhook
// Set webhook URL: POST https://api.telegram.org/bot<TOKEN>/setWebhook?url=https://your-domain.com/webhook
Route::post('/webhook/{bot?}', TelegramWebhookController::class)
    ->name('telegram.webhook');
