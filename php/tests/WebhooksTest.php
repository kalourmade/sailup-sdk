<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Tests;

use Kalourmade\Sailup\Exceptions\InvalidSignatureError;
use Kalourmade\Sailup\Webhooks;
use PHPUnit\Framework\TestCase;

final class WebhooksTest extends TestCase
{
    private const SECRET = 'whsec_test';

    private function sign(string $timestamp, string $rawBody): string
    {
        return hash_hmac('sha256', "{$timestamp}.{$rawBody}", self::SECRET);
    }

    public function test_verify_returns_decoded_payload_for_valid_signature(): void
    {
        $rawBody = json_encode(['event' => 'message.delivered', 'data' => ['message_id' => 'msg_123']]);
        $timestamp = '1700000000';
        $signature = $this->sign($timestamp, $rawBody);

        $event = Webhooks::verify($rawBody, [
            'X-Sailup-Signature' => $signature,
            'X-Sailup-Timestamp' => $timestamp,
        ], self::SECRET);

        $this->assertSame('message.delivered', $event['event']);
    }

    public function test_verify_throws_on_tampered_body(): void
    {
        $rawBody = json_encode(['event' => 'message.delivered']);
        $timestamp = '1700000000';
        $signature = $this->sign($timestamp, $rawBody);
        $tampered = json_encode(['event' => 'message.failed']);

        $this->expectException(InvalidSignatureError::class);
        Webhooks::verify($tampered, [
            'X-Sailup-Signature' => $signature,
            'X-Sailup-Timestamp' => $timestamp,
        ], self::SECRET);
    }

    public function test_verify_throws_on_missing_headers(): void
    {
        $this->expectException(InvalidSignatureError::class);
        Webhooks::verify('{}', [], self::SECRET);
    }
}
