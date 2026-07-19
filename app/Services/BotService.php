<?php

namespace App\Services;

use App\DTO\IncomingMessage;

/**
 * Platform-agnostic business logic.
 *
 * This service is called by every adapter after extracting
 * an IncomingMessage. It contains the actual bot behaviour.
 *
 * Every project overrides this file with its own logic
 * (decode documents, read meters, check counterparties, etc.)
 */
class BotService
{
    /**
     * Process an incoming message and return a response text.
     *
     * Override this in your project with real logic.
     */
    public function process(IncomingMessage $message): string
    {
        // ----- Placeholder: replace with real project logic -----

        $text = $message->text ?? '';

        if (str_starts_with($text, '/start')) {
            return "Привет! 👋\n\n"
                ."Я бот-шаблон.\n"
                ."Платформа: {$message->platform}\n"
                ."Твой ID: {$message->userId}\n\n"
                .'Замени BotService::process() на свою бизнес-логику.';
        }

        if ($message->hasPhoto()) {
            return '📸 Фото получено. Обработка...';
        }

        if ($message->hasText()) {
            return "Получено: «{$message->text}»\n"
                .'Я пока не знаю такой команды.';
        }

        return 'Пожалуйста, отправьте текст или фото.';
    }
}
