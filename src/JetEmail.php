<?php

use GuzzleHttp\Client as GuzzleClient;
use JetEmail\Client;
use JetEmail\Transporters\HttpTransporter;
use JetEmail\ValueObjects\ApiKey;
use JetEmail\ValueObjects\Transporter\BaseUri;
use JetEmail\ValueObjects\Transporter\Headers;

final class JetEmail
{
    /**
     * Create a new JetEmail client with the given API key.
     */
    public static function client(string $apiKey, string $baseUri = 'https://api.jetemail.com'): Client
    {
        $apiKey = ApiKey::from($apiKey);
        $baseUri = BaseUri::from($baseUri);
        $headers = Headers::withAuthorization($apiKey);

        $client = new GuzzleClient();
        $transporter = new HttpTransporter($client, $baseUri, $headers);

        return new Client($transporter);
    }
}
