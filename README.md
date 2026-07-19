# Laravel Bot Template

[![Lint & Test](https://github.com/AlSokolov2/laravel-bot-template/actions/workflows/test.yml/badge.svg)](https://github.com/AlSokolov2/laravel-bot-template/actions/workflows/test.yml)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-blue)](https://php.net)
[![Laravel](https://img.shields.io/badge/Laravel-13-red)](https://laravel.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Laravel boilerplate for fast bootstrapping of multi-platform bots:
**Telegram**, **MAX** (VK national messenger), and **VK Mini Apps**.

> Built with **Platform Adapter Architecture** — platform-agnostic business logic,
> thin platform-specific adapters. Add a new platform without changing your core code.

---

## Features

- Laravel 13 + Telegram Bot SDK
- **Platform Adapter Architecture**: `IncomingMessage` DTO → `PlatformAdapterInterface` → `BotService`
- Telegram: full support (webhook handler, photo/document download, commands)
- MAX: adapter skeleton (ready to activate pre-release)
- VK Mini Apps: served as REST API (add a controller, reuse `BotService`)
- Shared MySQL 8.0 + Redis 7 via Docker Compose
- GPLv3 licensed — free software

## Quick start

```bash
git clone https://github.com/AlSokolov2/laravel-bot-template.git my-bot
cd my-bot
composer install
cp .env.example .env
# Edit .env: TELEGRAM_BOT_TOKEN, DB_DATABASE
php artisan key:generate
php artisan migrate
```

### Set Telegram webhook

```bash
# With ngrok (dev)
ngrok http 8080
# https://api.telegram.org/bot<TOKEN>/setWebhook?url=https://<ngrok>.ngrok-free.app/webhook/telegram
```

### Run tests

```bash
composer lint     # Laravel Pint (dry-run)
composer test     # PHPUnit
```

## Architecture

```
User → [Telegram | MAX | VK Mini App]
  → Webhook / REST
  → Adapter::extractMessage()       ← platform-specific
  → IncomingMessage DTO             ← platform-agnostic
  → BotService::process()           ← your business logic here
  → Adapter::sendMessage()          ← platform-specific
  → User
```

- **`BotService`** has zero platform knowledge. Override `process()` with your logic.
- **Adapters** translate platform-specific formats into the common `IncomingMessage` DTO.
- **Adding a new platform** = 1 new adapter + 1 new controller. That's it.

## Project structure

```
app/
├── DTO/
│   └── IncomingMessage.php              # Platform-agnostic message
├── Platforms/
│   ├── Contracts/
│   │   └── PlatformAdapterInterface.php
│   ├── Telegram/
│   │   └── TelegramAdapter.php          # Full implementation
│   └── Max/
│       └── MaxAdapter.php               # Skeleton (pre-release)
├── Services/
│   └── BotService.php                   # Business logic
└── Http/Controllers/
    ├── TelegramWebhookController.php    # POST /webhook/telegram
    └── MaxWebhookController.php         # POST /webhook/max (skeleton)
```

## Built with this template

Projects in my portfolio that use this architecture:

- _[Coming soon]_

## License

GPLv3. See [LICENSE](LICENSE).

Copyright (c) 2026 Alexander Sokolov.
