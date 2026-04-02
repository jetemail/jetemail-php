<?php

declare(strict_types=1);

namespace JetEmail\ValueObjects\Transporter;

final class BaseUri
{
    private function __construct(
        public readonly string $value,
    ) {
    }

    public static function from(string $value): self
    {
        $value = rtrim($value, '/') . '/';

        if (! str_starts_with($value, 'http')) {
            $value = 'https://' . $value;
        }

        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
