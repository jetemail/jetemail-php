<?php

declare(strict_types=1);

use JetEmail\Resource;
use JetEmail\Service\Batch;
use JetEmail\Tests\Fixtures\MockTransporter;

it('sends a batch of emails and returns a resource', function () {
    $transporter = new MockTransporter([
        'summary' => ['total' => 2, 'success' => 2, 'failed' => 0],
        'results' => [
            ['id' => 'msg_1', 'status' => 201],
            ['id' => 'msg_2', 'status' => 201],
        ],
    ]);

    $batch = new Batch($transporter);

    $result = $batch->send([
        [
            'from' => 'test@example.com',
            'to' => 'user1@example.com',
            'subject' => 'Hello 1',
            'html' => '<p>Hi 1</p>',
        ],
        [
            'from' => 'test@example.com',
            'to' => 'user2@example.com',
            'subject' => 'Hello 2',
            'html' => '<p>Hi 2</p>',
        ],
    ]);

    expect($result)->toBeInstanceOf(Resource::class)
        ->and($result->summary['total'])->toBe(2)
        ->and($result->results)->toHaveCount(2);
});

it('sends a single email as batch', function () {
    $transporter = new MockTransporter([
        'summary' => ['total' => 1, 'success' => 1, 'failed' => 0],
        'results' => [
            ['id' => 'msg_1', 'status' => 201],
        ],
    ]);

    $batch = new Batch($transporter);

    $result = $batch->send([
        [
            'from' => 'test@example.com',
            'to' => 'user@example.com',
            'subject' => 'Solo',
            'html' => '<p>Solo batch</p>',
        ],
    ]);

    expect($result->summary['total'])->toBe(1);
});
