<?php

declare(strict_types=1);

use JetEmail\ValueObjects\ApiKey;
use JetEmail\ValueObjects\Transporter\BaseUri;
use JetEmail\ValueObjects\Transporter\Headers;
use JetEmail\ValueObjects\Transporter\Payload;

function buildRequest(Payload $payload): \Psr\Http\Message\RequestInterface
{
    $baseUri = BaseUri::from('https://api.jetemail.com');
    $headers = Headers::withAuthorization(ApiKey::from('test-key'));

    return $payload->toRequest($baseUri, $headers);
}

it('builds a POST create request with json body', function () {
    $payload = Payload::create('email', [
        'from' => 'test@example.com',
        'to' => 'recipient@example.com',
        'subject' => 'Hello',
        'html' => '<p>Hi</p>',
    ]);

    $request = buildRequest($payload);

    expect($request->getMethod())->toBe('POST')
        ->and((string) $request->getUri())->toBe('https://api.jetemail.com/email')
        ->and($request->getHeaderLine('Authorization'))->toBe('Bearer test-key')
        ->and($request->getHeaderLine('Content-Type'))->toBe('application/json');

    $body = json_decode((string) $request->getBody(), true);
    expect($body['from'])->toBe('test@example.com')
        ->and($body['subject'])->toBe('Hello');
});

it('builds a GET list request with query parameters', function () {
    $payload = Payload::list('outbound/logs', ['limit' => 50, 'page' => 2]);

    $request = buildRequest($payload);

    expect($request->getMethod())->toBe('GET')
        ->and((string) $request->getUri())->toContain('outbound/logs?')
        ->and((string) $request->getUri())->toContain('limit=50')
        ->and((string) $request->getUri())->toContain('page=2')
        ->and((string) $request->getBody())->toBe('');
});

it('builds a GET list request without query when empty', function () {
    $payload = Payload::list('email');

    $request = buildRequest($payload);

    expect($request->getMethod())->toBe('GET')
        ->and((string) $request->getUri())->toBe('https://api.jetemail.com/email');
});

it('builds a DELETE request with body', function () {
    $payload = Payload::delete('outbound/smarthost', ['username' => 'smtp-user']);

    $request = buildRequest($payload);

    expect($request->getMethod())->toBe('DELETE');

    $body = json_decode((string) $request->getBody(), true);
    expect($body['username'])->toBe('smtp-user');
});

it('builds a PATCH update request', function () {
    $payload = Payload::update('webhooks', ['uuid' => 'wh_123', 'name' => 'Updated']);

    $request = buildRequest($payload);

    expect($request->getMethod())->toBe('PATCH')
        ->and((string) $request->getUri())->toBe('https://api.jetemail.com/webhooks');

    $body = json_decode((string) $request->getBody(), true);
    expect($body['uuid'])->toBe('wh_123');
});

it('builds a POST action request', function () {
    $payload = Payload::post('email-batch', ['emails' => []]);

    $request = buildRequest($payload);

    expect($request->getMethod())->toBe('POST')
        ->and((string) $request->getUri())->toBe('https://api.jetemail.com/email-batch');
});
