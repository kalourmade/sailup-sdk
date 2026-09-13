<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Sailup\SailupClient;

$client = new SailupClient(getenv('SAILUP_API_KEY') ?: throw new RuntimeException('Set SAILUP_API_KEY'));

$message = $client->sms->send(
    from: 'MyBrand',
    to: ['233241234567'],
    body: 'Hello from the Sailup PHP SDK'
);

printf("Sent %s — delivery_status=%s\n", $message->id, $message->deliveryStatus->value);
