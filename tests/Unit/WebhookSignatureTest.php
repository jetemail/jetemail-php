<?php

declare(strict_types=1);

use JetEmail\Exceptions\WebhookSignatureVerificationException;
use JetEmail\WebhookSignature;

it('verifies a valid signature', function () {
    $secret = 'test-webhook-secret';
    $payload = '{"type":"outbound.delivered","id":"evt_123"}';
    $timestamp = (string) time();
    $signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

    $result = WebhookSignature::verify($payload, $signature, $timestamp, $secret);

    expect($result)->toBeTrue();
});

it('rejects an invalid signature', function () {
    $secret = 'test-webhook-secret';
    $payload = '{"type":"outbound.delivered","id":"evt_123"}';
    $timestamp = (string) time();
    $signature = 'sha256=' . hash_hmac('sha256', $payload, 'wrong-secret');

    WebhookSignature::verify($payload, $signature, $timestamp, $secret);
})->throws(WebhookSignatureVerificationException::class, 'Webhook signature verification failed.');

it('rejects an expired timestamp', function () {
    $secret = 'test-webhook-secret';
    $payload = '{"type":"outbound.delivered","id":"evt_123"}';
    $timestamp = (string) (time() - 600);
    $signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

    WebhookSignature::verify($payload, $signature, $timestamp, $secret);
})->throws(WebhookSignatureVerificationException::class, 'Webhook timestamp is outside the tolerance zone.');

it('accepts a custom tolerance', function () {
    $secret = 'test-webhook-secret';
    $payload = '{"type":"outbound.delivered"}';
    $timestamp = (string) (time() - 500);
    $signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

    $result = WebhookSignature::verify($payload, $signature, $timestamp, $secret, tolerance: 600);

    expect($result)->toBeTrue();
});

it('skips timestamp check when tolerance is zero', function () {
    $secret = 'test-webhook-secret';
    $payload = '{"type":"outbound.delivered"}';
    $timestamp = (string) (time() - 99999);
    $signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

    $result = WebhookSignature::verify($payload, $signature, $timestamp, $secret, tolerance: 0);

    expect($result)->toBeTrue();
});
