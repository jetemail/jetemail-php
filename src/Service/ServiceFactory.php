<?php

declare(strict_types=1);

namespace JetEmail\Service;

use JetEmail\Contracts\Transporter;

final class ServiceFactory
{
    /** @var array<string, class-string<Service>> */
    private static array $classMap = [
        'emails' => Email::class,
        'batch' => Batch::class,
    ];

    /** @var array<string, Service> */
    private array $services = [];

    public function __construct(
        private readonly Transporter $transporter,
    ) {
    }

    public function make(string $name): Service
    {
        if (isset($this->services[$name])) {
            return $this->services[$name];
        }

        $class = self::$classMap[$name] ?? throw new \InvalidArgumentException(
            "Unknown service: {$name}. Available services: " . implode(', ', array_keys(self::$classMap)),
        );

        return $this->services[$name] = new $class($this->transporter);
    }
}
