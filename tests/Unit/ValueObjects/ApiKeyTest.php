<?php

declare(strict_types=1);

use JetEmail\ValueObjects\ApiKey;

it('creates an api key from a string', function () {
    $key = ApiKey::from('transactional_abc123');

    expect($key->toString())->toBe('transactional_abc123')
        ->and($key->value)->toBe('transactional_abc123');
});
