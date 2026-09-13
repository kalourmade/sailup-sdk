<?php

declare(strict_types=1);

namespace Kalourmade\Sailup;

use Kalourmade\Sailup\Exceptions\InvalidSignatureError;

final class Webhooks
{
    /**
     * @param array<string,string> $headers
     * @return array<string,mixed>
     */
    public static function verify(string $rawBody, array $headers, string $secret): array
    {
        $normalized = array_change_key_case($headers, CASE_LOWER);
        $signature = $normalized['x-sailup-signature'] ?? null;
        $timestamp = $normalized['x-sailup-timestamp'] ?? null;

        if ($signature === null || $timestamp === null) {
            throw new InvalidSignatureError('Missing signature headers');
        }

        $expected = hash_hmac('sha256', "{$timestamp}.{$rawBody}", $secret);

        if (!hash_equals($expected, $signature)) {
            throw new InvalidSignatureError('Signature mismatch');
        }

        return json_decode($rawBody, true, flags: JSON_THROW_ON_ERROR);
    }
}
