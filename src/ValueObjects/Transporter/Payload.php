<?php

declare(strict_types=1);

namespace JetEmail\ValueObjects\Transporter;

use GuzzleHttp\Psr7\Request;
use JetEmail\Enums\Transporter\ContentType;
use JetEmail\Enums\Transporter\Method;

final class Payload
{
    /**
     * @param  array<string, mixed>  $parameters
     */
    private function __construct(
        private readonly Method $method,
        private readonly string $uri,
        private readonly array $parameters = [],
        private readonly ContentType $contentType = ContentType::JSON,
    ) {
    }

    /**
     * GET request to list resources.
     *
     * @param  array<string, mixed>  $query
     */
    public static function list(string $uri, array $query = []): self
    {
        return new self(Method::GET, $uri, $query);
    }

    /**
     * GET request to retrieve a single resource.
     */
    public static function get(string $uri, string $id): self
    {
        return new self(Method::GET, "{$uri}/{$id}");
    }

    /**
     * POST request to create a resource.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function create(string $uri, array $parameters): self
    {
        return new self(Method::POST, $uri, $parameters);
    }

    /**
     * PATCH request to update a resource.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function update(string $uri, array $parameters): self
    {
        return new self(Method::PATCH, $uri, $parameters);
    }

    /**
     * PATCH request to update a specific resource by ID.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function updateById(string $uri, string $id, array $parameters): self
    {
        return new self(Method::PATCH, "{$uri}/{$id}", $parameters);
    }

    /**
     * DELETE request to remove a resource.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function delete(string $uri, array $parameters = []): self
    {
        return new self(Method::DELETE, $uri, $parameters);
    }

    /**
     * DELETE request to remove a resource by ID.
     */
    public static function deleteById(string $uri, string $id): self
    {
        return new self(Method::DELETE, "{$uri}/{$id}");
    }

    /**
     * POST request for a custom action on a resource.
     *
     * @param  array<string, mixed>  $parameters
     */
    public static function post(string $uri, array $parameters = []): self
    {
        return new self(Method::POST, $uri, $parameters);
    }

    /**
     * Build a PSR-7 request.
     */
    public function toRequest(BaseUri $baseUri, Headers $headers): Request
    {
        $uri = $baseUri->toString() . $this->uri;

        if ($this->method === Method::GET && ! empty($this->parameters)) {
            $uri .= '?' . http_build_query($this->parameters);
        }

        $body = null;

        if ($this->method !== Method::GET && ! empty($this->parameters)) {
            $body = json_encode($this->parameters, JSON_THROW_ON_ERROR);
        }

        return new Request(
            $this->method->value,
            $uri,
            $headers->toArray(),
            $body,
        );
    }
}
