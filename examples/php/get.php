<?php

require __DIR__ . '/../../vendor/autoload.php';

use Kalourmade\Sailup\SailupClient;

$messageId = $argv[1] ?? throw new RuntimeException('Usage: php get.php <message_id>');
$client = new SailupClient(getenv('SAILUP_API_KEY') ?: throw new RuntimeException('Set SAILUP_API_KEY'));

$message = $client->sms->get($messageId);

printf("%s — %s (%s)\n", $message->id, $message->body, $message->deliveryStatus->value);
