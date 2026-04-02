<?php

declare(strict_types=1);

namespace JetEmail;

use JetEmail\Contracts\Transporter;
use JetEmail\Service\ServiceFactory;

/**
 * @property-read Service\Email $emails
 * @property-read Service\Batch $batch
 */
final class Client
{
    private readonly ServiceFactory $factory;

    public function __construct(
        private readonly Transporter $transporter,
    ) {
        $this->factory = new ServiceFactory($this->transporter);
    }

    /**
     * @param  string  $name
     * @return Service\Service
     */
    public function __get(string $name): Service\Service
    {
        return $this->factory->make($name);
    }
}
