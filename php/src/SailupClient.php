<?php

declare(strict_types=1);

namespace Kalourmade\Sailup;

use Kalourmade\Sailup\Http\CurlHttpTransport;
use Kalourmade\Sailup\Sms\SmsResource;

final class SailupClient
{
    public readonly SmsResource $sms;

    public function __construct(
        string $apiKey,
        string $baseUrl = 'https://api.sailup.io/v1',
        int $timeout = 30
    ) {
        $transport = new CurlHttpTransport($apiKey, $baseUrl, $timeout);
        $this->sms = new SmsResource($transport);
    }
}
