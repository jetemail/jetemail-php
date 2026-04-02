<?php

declare(strict_types=1);

namespace JetEmail\Contracts;

use JetEmail\ValueObjects\Transporter\Payload;

interface Transporter
{
    /**
     * Send a request and return the decoded response.
     *
     * @return array<string, mixed>
     */
    public function request(Payload $payload): array;
}
