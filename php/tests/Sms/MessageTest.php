<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Tests\Sms;

use Kalourmade\Sailup\Sms\DeliveryStatus;
use Kalourmade\Sailup\Sms\Message;
use PHPUnit\Framework\TestCase;

final class MessageTest extends TestCase
{
    public function test_from_array_maps_all_fields(): void
    {
        $message = Message::fromArray([
            'id' => 'msg_123',
            'to' => ['233241234567'],
            'sender' => 'MyBrand',
            'body' => 'Hello',
            'quantity' => 1,
            'delivery_status' => 'delivered',
            'created_at' => '2026-09-12T10:00:00Z',
        ]);

        $this->assertSame('msg_123', $message->id);
        $this->assertSame(['233241234567'], $message->to);
        $this->assertSame('MyBrand', $message->sender);
        $this->assertSame('Hello', $message->body);
        $this->assertSame(1, $message->quantity);
        $this->assertSame(DeliveryStatus::Delivered, $message->deliveryStatus);
        $this->assertSame('2026-09-12T10:00:00Z', $message->createdAt);
    }

    public function test_from_array_wraps_scalar_to_in_array(): void
    {
        $message = Message::fromArray([
            'id' => 'msg_123',
            'to' => '233241234567',
            'sender' => null,
            'body' => 'Hello',
            'quantity' => 1,
            'delivery_status' => 'pending',
            'created_at' => '2026-09-12T10:00:00Z',
        ]);

        $this->assertSame(['233241234567'], $message->to);
        $this->assertNull($message->sender);
    }
}
