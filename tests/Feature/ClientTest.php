<?php

declare(strict_types=1);

use JetEmail\Client;
use JetEmail\Service\Batch;
use JetEmail\Service\Email;
use JetEmail\Tests\Fixtures\MockTransporter;

it('resolves the emails service', function () {
    $client = new Client(new MockTransporter());

    expect($client->emails)->toBeInstanceOf(Email::class);
});

it('resolves the batch service', function () {
    $client = new Client(new MockTransporter());

    expect($client->batch)->toBeInstanceOf(Batch::class);
});

it('returns the same service instance on repeated access', function () {
    $client = new Client(new MockTransporter());

    $emails1 = $client->emails;
    $emails2 = $client->emails;

    expect($emails1)->toBe($emails2);
});

it('throws for unknown service names', function () {
    $client = new Client(new MockTransporter());

    $client->nonexistent;
})->throws(InvalidArgumentException::class, 'Unknown service: nonexistent');
