<?php

declare(strict_types=1);

use JetEmail\ValueObjects\Transporter\BaseUri;

it('appends a trailing slash', function () {
    $uri = BaseUri::from('https://api.jetemail.com');

    expect($uri->toString())->toBe('https://api.jetemail.com/');
});

it('does not duplicate trailing slash', function () {
    $uri = BaseUri::from('https://api.jetemail.com/');

    expect($uri->toString())->toBe('https://api.jetemail.com/');
});

it('prepends https when missing', function () {
    $uri = BaseUri::from('api.jetemail.com');

    expect($uri->toString())->toBe('https://api.jetemail.com/');
});

it('preserves http scheme', function () {
    $uri = BaseUri::from('http://localhost:8080');

    expect($uri->toString())->toBe('http://localhost:8080/');
});
