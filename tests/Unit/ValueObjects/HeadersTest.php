<?php

declare(strict_types=1);

use JetEmail\ValueObjects\ApiKey;
use JetEmail\ValueObjects\Transporter\Headers;

it('creates headers with authorization', function () {
    $headers = Headers::withAuthorization(ApiKey::from('transactional_abc123'));

    $array = $headers->toArray();

    expect($array['Authorization'])->toBe('Bearer transactional_abc123')
        ->and($array['Accept'])->toBe('application/json')
        ->and($array['Content-Type'])->toBe('application/json')
        ->and($array['User-Agent'])->toBe('jetemail-php/1.0');
});
