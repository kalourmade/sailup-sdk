# Sailup PHP SDK

`kalourmade/sailup-sms` — PHP SDK for the Sailup SMS API.

## Install

```bash
composer require kalourmade/sailup-sms
```

Requires PHP 8.1+. No runtime dependencies.

## Quickstart

```php
use Kalourmade\Sailup\SailupClient;

$client = new SailupClient(getenv('SAILUP_API_KEY'));

$message = $client->sms->send(
    from: 'MyBrand',
    to: ['233241234567'],
    body: 'Hello from Sailup'
);
```

Full runnable examples: [`examples/php/send.php`](../examples/php/send.php),
[`list.php`](../examples/php/list.php), [`get.php`](../examples/php/get.php).

## Error handling

```php
use Kalourmade\Sailup\Exceptions\{AuthenticationError, ValidationError, RateLimitError, ApiError, NetworkError};

try {
    $client->sms->send(from: 'MyBrand', to: ['233241234567'], body: 'Hi');
} catch (AuthenticationError $e) {
    // invalid/missing API key
} catch (ValidationError $e) {
    // bad request payload
} catch (RateLimitError $e) {
    // back off and retry
} catch (ApiError $e) {
    // other API error — $e->statusCode, $e->rawBody
} catch (NetworkError $e) {
    // couldn't reach Sailup
}
```

## Verifying webhooks

```php
use Kalourmade\Sailup\Webhooks;
use Kalourmade\Sailup\Exceptions\InvalidSignatureError;

try {
    $event = Webhooks::verify($rawRequestBody, getallheaders(), getenv('SAILUP_WEBHOOK_SECRET'));
} catch (InvalidSignatureError $e) {
    http_response_code(400);
    exit;
}
```

Full example: [`examples/php/verify_webhook.php`](../examples/php/verify_webhook.php).
