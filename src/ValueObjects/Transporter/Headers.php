<?php

declare(strict_types=1);

namespace JetEmail\ValueObjects\Transporter;

use JetEmail\ValueObjects\ApiKey;

final class Headers
{
    /**
     * @param  array<string, string>  $headers
     */
    private function __construct(
        private readonly array $headers,
    ) {}

    public static function withAuthorization(ApiKey $apiKey): self
    {
        return new self([
            'Authorization' => 'Bearer ' . $apiKey->toString(),
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'jetemail-php/1.0',
        ]);
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return $this->headers;
    }
}
