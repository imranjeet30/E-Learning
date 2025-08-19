<?php
namespace App\Services\Payments;

use Razorpay\Api\Api;

class RazorpayGateway implements PaymentGatewayInterface
{
    public function __construct(
        private readonly string $key,
        private readonly string $secret,
        private readonly string $whSecret
    ) {}

    protected function client(): Api { return new Api($this->key, $this->secret); }

    public function initiate(int $userId, int $amountMinor, string $currency, array $meta = []): array
    {
        $order = $this->client()->order->create([
            'amount' => $amountMinor,
            'currency' => strtoupper($currency),
            'receipt' => 'rcpt_' . uniqid(),
            'notes' => array_merge($meta, ['user_id' => (string)$userId]),
        ]);
        return [
            'external_id' => $order['id'], // order_*
            'order' => $order->toArray(),
            'key_id' => $this->key,
            'status' => 'created',
        ];
    }

    public function capture(string $externalPaymentId, array $payload = []): array
    {
        // Razorpay is usually captured after webhook or client callback with signature
        $payment = $this->client()->payment->fetch($externalPaymentId);
        if (($payment['status'] ?? '') === 'authorized') {
            $payment = $this->client()->payment->capture($externalPaymentId, $payment['amount']);
        }
        return ['status'=>$payment['status'],'raw'=>$payment->toArray()];
    }

    public function verifyWebhook(string $payload, array $headers): array
    {
        $sig = $headers['x-razorpay-signature'] ?? $headers['X-Razorpay-Signature'] ?? '';
        $expectedSignature = hash_hmac('sha256', $payload, $this->whSecret);
        if (!hash_equals($expectedSignature, $sig)) {
            throw new \RuntimeException('Invalid signature');
        }
        return json_decode($payload, true);
    }

    public function interpretEvent(array $event): array
    {
        $eventType = $event['event'] ?? '';
        $payload = $event['payload']['payment']['entity'] ?? [];
        $externalId = $payload['id'] ?? null;

        return match ($eventType) {
            'payment.captured' => ['status'=>'succeeded','external_id'=>$externalId],
            'payment.failed'   => ['status'=>'failed','external_id'=>$externalId],
            default => ['status'=>'ignored','external_id'=>$externalId],
        };
    }
}
