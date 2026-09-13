<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Tests\Http;

use Kalourmade\Sailup\Exceptions\ApiError;
use Kalourmade\Sailup\Exceptions\AuthenticationError;
use Kalourmade\Sailup\Exceptions\RateLimitError;
use Kalourmade\Sailup\Exceptions\ValidationError;
use Kalourmade\Sailup\Http\ErrorMapper;
use PHPUnit\Framework\TestCase;

final class ErrorMapperTest extends TestCase
{
    public function test_ok_status_does_not_throw(): void
    {
        ErrorMapper::throwIfError(200, '{}');
        $this->addToAssertionCount(1);
    }

    public function test_401_throws_authentication_error(): void
    {
        $this->expectException(AuthenticationError::class);
        ErrorMapper::throwIfError(401, '{}');
    }

    public function test_429_throws_rate_limit_error(): void
    {
        $this->expectException(RateLimitError::class);
        ErrorMapper::throwIfError(429, '{}');
    }

    public function test_400_and_422_throw_validation_error(): void
    {
        $this->expectException(ValidationError::class);
        ErrorMapper::throwIfError(422, '{}');
    }

    public function test_other_4xx_5xx_throw_api_error_with_status_and_body(): void
    {
        try {
            ErrorMapper::throwIfError(503, '{"detail":"down"}');
            $this->fail('Expected ApiError');
        } catch (ApiError $e) {
            $this->assertSame(503, $e->statusCode);
            $this->assertSame('{"detail":"down"}', $e->rawBody);
        }
    }
}
