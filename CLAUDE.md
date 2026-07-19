# Laravel Bot Template (v2 — multi-platform)

Шаблон для быстрого старта ботов на Laravel с поддержкой нескольких платформ.

## Что внутри

- Laravel 13
- Telegram Bot SDK (irazasyed/telegram-bot-sdk)
- **Platform Adapter Architecture** — единая бизнес-логика, платформо-специфичные адаптеры
- Telegram: полная поддержка (Adapter + Webhook)
- MAX: skeleton адаптера (будет активирован перед релизом)
- VK Mini App: обслуживается как REST API (отдельный контроллер в проекте)

## Архитектура

```
app/
├── DTO/
│   └── IncomingMessage.php                    # Абстрактное сообщение (platform-agnostic)
├── Platforms/
│   ├── Contracts/
│   │   └── PlatformAdapterInterface.php       # Интерфейс адаптера
│   ├── Telegram/
│   │   └── TelegramAdapter.php                # Telegram Update → IncomingMessage
│   └── Max/
│       └── MaxAdapter.php                     # MAX update → IncomingMessage (SKELETON)
├── Services/
│   └── BotService.php                         # Бизнес-логика (platform-agnostic)
├── Http/Controllers/
│   ├── TelegramWebhookController.php          # POST /webhook/telegram
│   └── MaxWebhookController.php               # POST /webhook/max (SKELETON)
routes/
├── web.php                                    # Webhook routes
bootstrap/
├── app.php                                    # CSRF-исключение для webhook/*
```

## Поток сообщения

```
Пользователь → [Telegram | MAX | VK] → Webhook/REST
    → Adapter::extractMessage() → IncomingMessage DTO
    → BotService::process() → response text
    → Adapter::sendMessage() → Пользователь
```

BotService ничего не знает о платформе. Добавить новую платформу = 1 новый адаптер + 1 контроллер.

## Как создать новый проект из шаблона

```bash
cd ~/projects
cp -r laravel-bot-template my-new-bot
cd my-new-bot
rm -rf .git
git init
# Настроить .env: TELEGRAM_BOT_TOKEN, DB_DATABASE
composer install
php artisan key:generate
php artisan migrate
```

### Установить webhook

```bash
# Telegram
php artisan tinker --execute="
  \$adapter = new App\Platforms\Telegram\TelegramAdapter(app('telegram'));
  \$adapter->setWebhook('https://your-domain.com/webhook/telegram');
"

# MAX (когда реализован)
php artisan tinker --execute="
  \$adapter = new App\Platforms\Max\MaxAdapter(env('MAX_BOT_TOKEN'), app('http.client'));
  \$adapter->setWebhook('https://your-domain.com/webhook/max');
"
```

## Быстрый старт (dev)

```bash
docker compose up -d                  # MySQL + Redis (общий в ~/projects/)
php artisan serve --port=8080
ngrok http 8080                       # Внешний URL для Telegram webhook
```

## Как переопределить логику

В проекте замени `BotService::process()` на свою бизнес-логику:

```php
public function process(IncomingMessage $message): string
{
    if ($message->hasPhoto()) {
        return $this->decodeDocument($message->photoUrl);
    }
    return $this->handleText($message->text);
}
```

## Добавление MAX перед релизом

1. Реализовать `MaxAdapter::sendMessage()` и `extractMessage()` через HTTP-клиент
2. Добавить MAX-бота в `config/telegram.php` (секция `bots.max`)
3. Установить webhook
4. Проверить работу через `/webhook/max`

## Стек для продакшена

- **Сервер:** VPS (Nginx + PHP-FPM)
- **База:** MySQL 8.0 (общий docker-compose в `~/projects/`)
- **Очереди:** Redis + Laravel Horizon
- **SSL:** Let's Encrypt (обязателен для webhook)
