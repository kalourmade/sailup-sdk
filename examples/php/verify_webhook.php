<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Sailup\Exceptions\InvalidSignatureError;
use Kalourmade\Sailup\Webhooks;

$secret = getenv('SAILUP_WEBHOOK_SECRET') ?: throw new RuntimeException('Set SAILUP_WEBHOOK_SECRET');

// Simulate a Sailup webhook delivery for demonstration purposes.
$rawBody = json_encode([
    'event' => 'message.delivered',
    'created_at' => '2026-09-12T10:00:00Z',
    'data' => ['message_id' => 'msg_123', 'delivery_status' => 'delivered'],
]);
$timestamp = (string) time();
$signature = hash_hmac('sha256', "{$timestamp}.{$rawBody}", $secret);

$event = Webhooks::verify($rawBody, [
    'X-Sailup-Signature' => $signature,
    'X-Sailup-Timestamp' => $timestamp,
], $secret);

printf("Verified event: %s\n", $event['event']);

// Demonstrate the failure case: a tampered body fails verification.
try {
    Webhooks::verify('{"event":"message.failed"}', [
        'X-Sailup-Signature' => $signature,
        'X-Sailup-Timestamp' => $timestamp,
    ], $secret);
} catch (InvalidSignatureError $e) {
    printf("Correctly rejected tampered payload: %s\n", $e->getMessage());
}
