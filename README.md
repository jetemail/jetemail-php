# JetEmail PHP SDK

The official PHP SDK for [JetEmail](https://jetemail.com) — send transactional emails with ease.

## Installation

```bash
composer require jetemail/jetemail-php
```

Requires PHP 8.1+ and Guzzle 7.5+.

> **Note:** You need to create an account at [jetemail.com](https://jetemail.com) to get a transactional API key before using this SDK.

## Quick Start

```php
$jetemail = JetEmail::client('your-api-key');

$email = $jetemail->emails->send([
    'from' => 'You <you@example.com>',
    'to' => ['recipient@example.com'],
    'subject' => 'Hello from JetEmail!',
    'html' => '<h1>Welcome</h1><p>Thanks for signing up.</p>',
]);

echo $email->id;
```

## Usage

### Sending Emails

```php
$jetemail = JetEmail::client('your-api-key');

// Send a single email
$email = $jetemail->emails->send([
    'from' => 'App <noreply@example.com>',
    'to' => 'user@example.com',
    'subject' => 'Order Confirmation',
    'html' => '<p>Your order has been confirmed.</p>',
    'text' => 'Your order has been confirmed.',
    'cc' => ['manager@example.com'],
    'bcc' => ['archive@example.com'],
    'reply_to' => 'support@example.com',
    'headers' => [
        'X-Custom-Header' => 'value',
    ],
    'attachments' => [
        [
            'filename' => 'receipt.pdf',
            'data' => base64_encode(file_get_contents('/path/to/receipt.pdf')),
        ],
    ],
]);
```

### Batch Emails

Send up to 100 emails in a single request:

```php
$result = $jetemail->batch->send([
    [
        'from' => 'App <noreply@example.com>',
        'to' => 'user1@example.com',
        'subject' => 'Hello User 1',
        'html' => '<p>Welcome!</p>',
    ],
    [
        'from' => 'App <noreply@example.com>',
        'to' => 'user2@example.com',
        'subject' => 'Hello User 2',
        'html' => '<p>Welcome!</p>',
    ],
]);
```

### Webhook Signature Verification

```php
use JetEmail\WebhookSignature;
use JetEmail\Exceptions\WebhookSignatureVerificationException;

try {
    WebhookSignature::verify(
        payload: $requestBody,
        signature: $_SERVER['HTTP_X_WEBHOOK_SIGNATURE'],
        timestamp: $_SERVER['HTTP_X_WEBHOOK_TIMESTAMP'],
        secret: 'your-webhook-secret',
    );

    // Signature is valid
} catch (WebhookSignatureVerificationException $e) {
    // Invalid signature
    http_response_code(401);
}
```

## Custom Base URL

```php
$jetemail = JetEmail::client('your-api-key', 'https://custom-api.example.com');
```

## Error Handling

```php
use JetEmail\Exceptions\ErrorException;
use JetEmail\Exceptions\TransporterException;

try {
    $jetemail->emails->send([...]);
} catch (ErrorException $e) {
    // API returned an error
    echo $e->getMessage();
    echo $e->getCode(); // HTTP status code
    print_r($e->getBody()); // Full error response
} catch (TransporterException $e) {
    // Network/transport error
    echo $e->getMessage();
}
```

## Available Services

| Service | Property | Description |
|---|---|---|
| Email | `$client->emails` | Send a single email |
| Batch | `$client->batch` | Send batch emails (up to 100) |

## License

MIT
