# Laravel Bot Template

Шаблон для быстрого старта Telegram-ботов на Laravel.

## Что внутри

- Laravel 11
- Telegram Bot SDK (irazasyed/telegram-bot-sdk)
- Готовый webhook-контроллер
- Обёртка TelegramService для бизнес-логики
- Исключение CSRF для маршрута webhook
- Пример обработки текста и фото

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
# Установить webhook:
php artisan tinker --execute="app(App\\Services\\TelegramService::class)->setWebhook('https://your-domain.com/webhook');"
```

## Структура

```
app/
├── Http/Controllers/
│   └── TelegramWebhookController.php   # Принимает POST от Telegram
├── Services/
│   └── TelegramService.php             # Бизнес-логика бота (handleUpdate)
config/
├── telegram.php                        # Конфигурация SDK
routes/
├── web.php                             # POST /webhook/{bot}
bootstrap/
├── app.php                             # CSRF-исключение для webhook
```

## Быстрый старт (dev)

```bash
docker compose up -d                  # MySQL + Redis
php artisan serve --port=8080
# В другом терминале — ngrok для локального webhook:
ngrok http 8080
# Установить webhook:
# https://api.telegram.org/bot<TOKEN>/setWebhook?url=https://<ngrok>.ngrok-free.app/webhook
```

## Стек для продакшена

- **Сервер:** любой VPS (Nginx + PHP-FPM)
- **База:** MySQL 8.0 (общий docker-compose в `~/projects/`)
- **Очереди:** Redis + Laravel Horizon
- **SSL:** Let's Encrypt (обязателен для Telegram webhook)
