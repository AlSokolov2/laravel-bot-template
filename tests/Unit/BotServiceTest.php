<?php

namespace Tests\Unit;

use App\DTO\IncomingMessage;
use App\Services\BotService;
use PHPUnit\Framework\TestCase;

class BotServiceTest extends TestCase
{
    private BotService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new BotService;
    }

    public function test_it_responds_to_start_command(): void
    {
        $msg = new IncomingMessage(
            platform: 'telegram',
            userId: '123',
            chatId: '456',
            text: '/start',
            photoUrl: null,
            documentUrl: null,
        );

        $response = $this->service->process($msg);

        $this->assertStringContainsString('Привет', $response);
        $this->assertStringContainsString('123', $response);   // userId echoed
        $this->assertStringContainsString('telegram', $response); // platform echoed
    }

    public function test_it_handles_photo(): void
    {
        $msg = new IncomingMessage(
            platform: 'telegram',
            userId: '1',
            chatId: '2',
            text: null,
            photoUrl: 'https://example.com/photo.jpg',
            documentUrl: null,
        );

        $response = $this->service->process($msg);

        $this->assertStringContainsString('Фото получено', $response);
    }

    public function test_it_handles_unknown_text(): void
    {
        $msg = new IncomingMessage(
            platform: 'telegram',
            userId: '1',
            chatId: '2',
            text: 'какая-то команда',
            photoUrl: null,
            documentUrl: null,
        );

        $response = $this->service->process($msg);

        $this->assertStringContainsString('какая-то команда', $response);
        $this->assertStringContainsString('не знаю', $response);
    }
}
