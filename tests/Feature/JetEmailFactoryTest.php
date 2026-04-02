<?php

declare(strict_types=1);

use JetEmail\Client;

it('creates a client via the static factory', function () {
    $client = JetEmail::client('test-api-key');

    expect($client)->toBeInstanceOf(Client::class);
});

it('creates a client with a custom base url', function () {
    $client = JetEmail::client('test-api-key', 'https://custom.example.com');

    expect($client)->toBeInstanceOf(Client::class);
});
