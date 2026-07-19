# Laravel Bot Template

Шаблон для быстрого старта Telegram-ботов на Laravel.

## Особенности

- **Laravel 11** + **Telegram Bot SDK** (irazasyed/telegram-bot-sdk)
- Готовый webhook-контроллер с исключением CSRF
- Обёртка `TelegramService` для бизнес-логики
- Пример обработки `/start`, текста и фото

## Требования

- PHP 8.2+
- Composer
- MySQL 8.0
- Redis (опционально)
- Telegram Bot Token ([@BotFather](https://t.me/BotFather))

## Установка

```bash
cp .env.example .env
# Заполнить: TELEGRAM_BOT_TOKEN, DB_DATABASE
composer install
php artisan key:generate
php artisan migrate
```

## Связанные проекты

Этот шаблон — часть портфеля проектов. См. каталог идей (приватный).
