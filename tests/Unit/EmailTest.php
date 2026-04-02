<?php

declare(strict_types=1);

use JetEmail\Resource;
use JetEmail\Service\Email;
use JetEmail\Tests\Fixtures\MockTransporter;

it('sends an email and returns a resource', function () {
    $transporter = new MockTransporter([
        'id' => 'msg_123',
        'response' => 'Queued',
    ]);

    $email = new Email($transporter);

    $result = $email->send([
        'from' => 'Test <test@example.com>',
        'to' => 'recipient@example.com',
        'subject' => 'Hello',
        'html' => '<p>World</p>',
    ]);

    expect($result)->toBeInstanceOf(Resource::class)
        ->and($result->id)->toBe('msg_123')
        ->and($result->response)->toBe('Queued');
});

it('sends an email with all optional fields', function () {
    $transporter = new MockTransporter(['id' => 'msg_456']);

    $email = new Email($transporter);

    $result = $email->send([
        'from' => 'App <noreply@example.com>',
        'to' => ['a@example.com', 'b@example.com'],
        'subject' => 'Test',
        'html' => '<p>Hello</p>',
        'text' => 'Hello',
        'cc' => ['cc@example.com'],
        'bcc' => ['bcc@example.com'],
        'reply_to' => 'reply@example.com',
        'headers' => ['X-Custom' => 'value'],
        'attachments' => [
            ['filename' => 'file.txt', 'data' => base64_encode('content')],
        ],
    ]);

    expect($result->id)->toBe('msg_456');
});

it('sends an email with text only', function () {
    $transporter = new MockTransporter(['id' => 'msg_789']);

    $email = new Email($transporter);

    $result = $email->send([
        'from' => 'test@example.com',
        'to' => 'recipient@example.com',
        'subject' => 'Plain text',
        'text' => 'Hello, plain text!',
    ]);

    expect($result->id)->toBe('msg_789');
});
