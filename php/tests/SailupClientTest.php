<?php

declare(strict_types=1);

namespace Kalourmade\Sailup\Tests;

use Kalourmade\Sailup\SailupClient;
use Kalourmade\Sailup\Sms\SmsResource;
use PHPUnit\Framework\TestCase;

final class SailupClientTest extends TestCase
{
    public function test_client_exposes_sms_resource(): void
    {
        $client = new SailupClient('sailup_test_key');

        $this->assertInstanceOf(SmsResource::class, $client->sms);
    }
}
