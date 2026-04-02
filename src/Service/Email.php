<?php

declare(strict_types=1);

namespace JetEmail\Service;

use JetEmail\Resource;
use JetEmail\ValueObjects\Transporter\Payload;

final class Email extends Service
{
    /**
     * Send an email.
     *
     * @param  array{
     *     from: string,
     *     to: string|string[],
     *     subject: string,
     *     html?: string,
     *     text?: string,
     *     cc?: string|string[],
     *     bcc?: string|string[],
     *     reply_to?: string|string[],
     *     headers?: array<string, string>,
     *     attachments?: array<int, array{filename: string, data: string}>,
     *     eu?: bool,
     * }  $parameters
     */
    public function send(array $parameters): Resource
    {
        $payload = Payload::create('email', $parameters);

        $result = $this->transporter->request($payload);

        return $this->createResource($result);
    }
}
