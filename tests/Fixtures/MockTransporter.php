<?php

declare(strict_types=1);

namespace JetEmail\Tests\Fixtures;

use JetEmail\Contracts\Transporter;
use JetEmail\ValueObjects\Transporter\Payload;

final class MockTransporter implements Transporter
{
    public ?Payload $lastPayload = null;

    /**
     * @param  array<string, mixed>  $response
     */
    public function __construct(
        private readonly array $response = [],
    ) {}

    public function request(Payload $payload): array
    {
        $this->lastPayload = $payload;

        return $this->response;
    }
}
