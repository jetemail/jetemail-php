<?php

declare(strict_types=1);

namespace JetEmail\Transporters;

use GuzzleHttp\Exception\ClientException;
use JetEmail\Contracts\Transporter;
use JetEmail\Exceptions\ErrorException;
use JetEmail\Exceptions\TransporterException;
use JetEmail\Exceptions\UnserializableResponse;
use JetEmail\ValueObjects\Transporter\BaseUri;
use JetEmail\ValueObjects\Transporter\Headers;
use JetEmail\ValueObjects\Transporter\Payload;
use JsonException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

final class HttpTransporter implements Transporter
{
    public function __construct(
        private readonly ClientInterface $client,
        private readonly BaseUri $baseUri,
        private readonly Headers $headers,
    ) {}

    /**
     * @inheritDoc
     */
    public function request(Payload $payload): array
    {
        $request = $payload->toRequest($this->baseUri, $this->headers);

        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientException $clientException) {
            if ($clientException->hasResponse()) {
                $this->throwApiError($clientException);
            }

            throw new TransporterException($clientException);
        } catch (ClientExceptionInterface $clientException) {
            throw new TransporterException($clientException);
        }

        $contents = $response->getBody()->getContents();

        if ($contents === '') {
            return [];
        }

        try {
            /** @var array<string, mixed> $data */
            $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $jsonException) {
            throw new UnserializableResponse($jsonException);
        }

        return $data;
    }

    /**
     * @throws ErrorException
     */
    private function throwApiError(ClientException $exception): never
    {
        $response = $exception->getResponse();
        $contents = $response->getBody()->getContents();
        $statusCode = $response->getStatusCode();

        try {
            /** @var array<string, mixed> $body */
            $body = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new ErrorException(['error' => $contents], $statusCode);
        }

        throw new ErrorException($body, $statusCode);
    }
}
