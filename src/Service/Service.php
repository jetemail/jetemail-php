<?php

declare(strict_types=1);

namespace JetEmail\Service;

use JetEmail\Contracts\Transporter;
use JetEmail\Resource;

abstract class Service
{
    public function __construct(
        protected readonly Transporter $transporter,
    ) {
    }

    /**
     * Create a single resource from API response attributes.
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function createResource(array $attributes): Resource
    {
        return Resource::from($attributes);
    }
}
