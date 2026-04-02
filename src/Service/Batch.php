<?php

declare(strict_types=1);

namespace JetEmail\Service;

use JetEmail\Resource;
use JetEmail\ValueObjects\Transporter\Payload;

final class Batch extends Service
{
    /**
     * Send a batch of emails (up to 100).
     *
     * @param  array<int, array{
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
     * }>  $emails
     */
    public function send(array $emails): Resource
    {
        $payload = Payload::create('email-batch', [
            'emails' => $emails,
        ]);

        $result = $this->transporter->request($payload);

        return $this->createResource($result);
    }
}
