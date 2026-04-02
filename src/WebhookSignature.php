<?php

declare(strict_types=1);

namespace JetEmail;

use JetEmail\Exceptions\WebhookSignatureVerificationException;

final class WebhookSignature
{
    private const DEFAULT_TOLERANCE = 300; // 5 minutes

    /**
     * Verify a webhook signature.
     *
     * @param  string  $payload     The raw request body
     * @param  string  $signature   The X-Webhook-Signature header value (sha256=...)
     * @param  string  $timestamp   The X-Webhook-Timestamp header value
     * @param  string  $secret      Your webhook secret
     * @param  int     $tolerance   Maximum age in seconds (default: 300)
     *
     * @throws WebhookSignatureVerificationException
     */
    public static function verify(
        string $payload,
        string $signature,
        string $timestamp,
        string $secret,
        int $tolerance = self::DEFAULT_TOLERANCE,
    ): bool {
        $timestampInt = (int) $timestamp;

        if ($tolerance > 0 && abs(time() - $timestampInt) > $tolerance) {
            throw new WebhookSignatureVerificationException(
                'Webhook timestamp is outside the tolerance zone.',
            );
        }

        $expectedSignature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        if (! hash_equals($expectedSignature, $signature)) {
            throw new WebhookSignatureVerificationException(
                'Webhook signature verification failed.',
            );
        }

        return true;
    }
}
