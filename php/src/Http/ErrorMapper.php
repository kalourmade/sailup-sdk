<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Http;

use Kalourmade\Sailup\Exceptions\ApiError;
use Kalourmade\Sailup\Exceptions\AuthenticationError;
use Kalourmade\Sailup\Exceptions\RateLimitError;
use Kalourmade\Sailup\Exceptions\ValidationError;

final class ErrorMapper
{
    public static function throwIfError(int $status, string $rawBody): void
    {
        if ($status < 400) {
            return;
        }

        $message = "Sailup API error (status {$status})";

        match (true) {
            $status === 401 => throw new AuthenticationError($message),
            $status === 429 => throw new RateLimitError($message),
            $status === 400 || $status === 422 => throw new ValidationError($message),
            default => throw new ApiError($status, $rawBody, $message),
        };
    }
}
