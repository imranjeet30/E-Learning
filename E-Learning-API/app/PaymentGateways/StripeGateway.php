<?php
namespace App\Services\Payments;

use Stripe\StripeClient;
use Illuminate\Support\Str;

class StripeGateway implements PaymentGatewayInterface
{
    public function __construct(private readonly StripeClient $stripe, private readonly string $whSecret) {}

    public function initiate(int $userId, int $amountMinor, string $currency, array $meta = []): array
    {
        $pi = $this->stripe->paymentIntents->create([
            'amount' => $amountMinor,
            'currency' => strtolower($currency),
            'metadata' => array_merge($meta, ['user_id'=>$userId]),
            'automatic_payment_methods' => ['enabled' => true],
        ]);
        return [
            'external_id' => $pi->id,
            'client_secret' => $pi->client_secret,
            'status' => $pi->status,
            'raw' => $pi->toArray(),
        ];
    }

    public function capture(string $externalId, array $payload = []): array
    {
        $pi = $this->stripe->paymentIntents->retrieve($externalId);
        if ($pi->status === 'requires_capture') {
            $pi = $this->stripe->paymentIntents->capture($externalId);
        }
        return ['status'=>$pi->status, 'raw'=>$pi->toArray()];
    }

    public function verifyWebhook(string $payload, array $headers): array
    {
        $sig = $headers['stripe-signature'] ?? $headers['Stripe-Signature'] ?? '';
        $event = \Stripe\Webhook::constructEvent($payload, $sig, $this->whSecret);
        return $event->toArray();
    }

    public function interpretEvent(array $event): array
    {
        $type = $event['type'] ?? '';
        $obj  = $event['data']['object'] ?? [];
        $externalId = $obj['id'] ?? null;

        return match ($type) {
            'payment_intent.succeeded' => ['status'=>'succeeded','external_id'=>$externalId],
            'payment_intent.payment_failed' => ['status'=>'failed','external_id'=>$externalId],
            default => ['status'=>'ignored','external_id'=>$externalId],
        };
    }
}
