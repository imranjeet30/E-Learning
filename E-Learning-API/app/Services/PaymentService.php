<?php
namespace App\Services;

use App\Models\Payment;
use App\Repositories\PaymentRepository;
use App\Services\Payments\GatewayFactory;

class PaymentService
{
    public function __construct(
        private readonly GatewayFactory $factory,
        private readonly PaymentRepository $payments
    ) {}

    public function initiate(int $userId, int $amountMinor, string $currency, string $gateway, array $meta = []): array
    {
        $driver = $this->factory->make($gateway);
        $result = $driver->initiate($userId, $amountMinor, $currency, $meta);

        $this->payments->create([
            'user_id' => $userId,
            'amount' => $amountMinor / 100,
            'currency' => $currency,
            'gateway' => $gateway,
            'status' => 'created',
            'external_id' => $result['external_id'] ?? null,
            'meta' => $result['raw'] ?? [],
        ]);

        return $result;
    }

    public function capture(string $gateway, string $externalId): array
    {
        $driver = $this->factory->make($gateway);
        return $driver->capture($externalId);
    }

    public function handleWebhook(string $gateway, string $payload, array $headers): array
    {
        $driver = $this->factory->make($gateway);
        $event = $driver->verifyWebhook($payload, $headers);
        return $driver->interpretEvent($event);
    }
}
