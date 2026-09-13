<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Tests\Exceptions;

use Kalourmade\Sailup\Exceptions\ApiError;
use Kalourmade\Sailup\Exceptions\AuthenticationError;
use Kalourmade\Sailup\Exceptions\InvalidSignatureError;
use Kalourmade\Sailup\Exceptions\NetworkError;
use Kalourmade\Sailup\Exceptions\RateLimitError;
use Kalourmade\Sailup\Exceptions\SailupError;
use Kalourmade\Sailup\Exceptions\ValidationError;
use PHPUnit\Framework\TestCase;

final class ExceptionHierarchyTest extends TestCase
{
    public function test_all_exceptions_extend_sailup_error(): void
    {
        $this->assertInstanceOf(SailupError::class, new AuthenticationError('x'));
        $this->assertInstanceOf(SailupError::class, new ValidationError('x'));
        $this->assertInstanceOf(SailupError::class, new RateLimitError('x'));
        $this->assertInstanceOf(SailupError::class, new NetworkError('x'));
        $this->assertInstanceOf(SailupError::class, new InvalidSignatureError('x'));
        $this->assertInstanceOf(SailupError::class, new ApiError(500, 'body', 'x'));
    }

    public function test_api_error_carries_status_and_raw_body(): void
    {
        $error = new ApiError(503, '{"detail":"down"}', 'Sailup API error (status 503)');

        $this->assertSame(503, $error->statusCode);
        $this->assertSame('{"detail":"down"}', $error->rawBody);
        $this->assertSame('Sailup API error (status 503)', $error->getMessage());
    }
}
