<?php
namespace App\Services\Payments;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    /** Create an order / payment intent to be confirmed client-side */
    public function initiate(int $userId, int $amountMinor, string $currency, array $meta = []): array;

    /** Capture/confirm after client approval (if gateway requires server capture) */
    public function capture(string $externalId, array $payload = []): array;

    /** Verify webhook signature and return parsed event */
    public function verifyWebhook(string $payload, array $headers): array;

    /** Map gateway event to unified statuses: created|succeeded|failed */
    public function interpretEvent(array $event): array;
}
