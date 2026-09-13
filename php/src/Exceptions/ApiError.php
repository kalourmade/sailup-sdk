<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Exceptions;

class ApiError extends SailupError
{
    public function __construct(
        public readonly int $statusCode,
        public readonly string $rawBody,
        string $message
    ) {
        parent::__construct($message);
    }
}
