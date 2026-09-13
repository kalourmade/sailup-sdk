<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Sailup\SailupClient;

$client = new SailupClient(getenv('SAILUP_API_KEY') ?: throw new RuntimeException('Set SAILUP_API_KEY'));

$page = $client->sms->list(page: 1, pageSize: 10);

foreach ($page->results as $message) {
    printf("%s — %s (%s)\n", $message->id, $message->body, $message->deliveryStatus->value);
}
