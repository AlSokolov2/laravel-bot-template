<?php

namespace App\Services;

use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

/**
 * Thin wrapper around Telegram Bot SDK.
 *
 * Register bot-specific response logic here, or extend
 * with separate handler classes per bot command / message type.
 */
class TelegramService
{
    public function __construct(
        private readonly Api $telegram,
    ) {}

    /**
     * Process an incoming update (message, callback query, etc.).
     */
    public function handleUpdate(Update $update): void
    {
        $message = $update->getMessage();

        if (! $message) {
            // Ignore updates that are not messages (e.g. inline queries).
            return;
        }

        $chatId = $message->chat->id;
        $text = $message->text ?? '';

        // ----- Placeholder: replace with real bot logic -----
        if (str_starts_with($text, '/start')) {
            $this->sendMessage($chatId, 'Привет! 👋 Бот на связи.');
        } elseif ($message->photo) {
            $this->sendMessage($chatId, '📸 Фото получено. Обработка...');
        } else {
            $this->sendMessage($chatId, 'Я пока не знаю такой команды.');
        }
    }

    /**
     * Send a plain-text message.
     */
    public function sendMessage(int|string $chatId, string $text): void
    {
        $this->telegram->sendMessage([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);
    }

    /**
     * Set / delete the webhook URL on Telegram servers.
     */
    public function setWebhook(string $url): void
    {
        $this->telegram->setWebhook(['url' => $url]);
    }

    public function deleteWebhook(): void
    {
        $this->telegram->deleteWebhook();
    }

    /**
     * Return a short description of registered bot commands.
     */
    public function getCommands(): array
    {
        return [
            ['command' => 'start', 'description' => 'Начать'],
        ];
    }
}
