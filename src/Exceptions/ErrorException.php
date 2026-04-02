<?php

declare(strict_types=1);

namespace JetEmail\Exceptions;

use Exception;

final class ErrorException extends Exception
{
    /**
     * @param  array<string, mixed>  $body
     */
    public function __construct(
        private readonly array $body,
        int $code = 0,
    ) {
        $message = $body['error'] ?? $body['message'] ?? 'Unknown API error';

        parent::__construct((string) $message, $code);
    }

    /**
     * @return array<string, mixed>
     */
    public function getBody(): array
    {
        return $this->body;
    }
}
