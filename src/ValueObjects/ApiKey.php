<?php

declare(strict_types=1);

namespace JetEmail\ValueObjects;

final class ApiKey
{
    private function __construct(
        public readonly string $value,
    ) {}

    public static function from(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
