<?php
namespace App\Services\Payments;

class StripePaymentService implements PaymentGatewayInterface
{
    public function charge(array $data)
    {
        // Example only – in real code use Stripe SDK
        return [
            'status' => 'success',
            'gateway' => 'stripe',
            'amount' => $data['amount'],
            'transaction_id' => 'txn_' . uniqid()
        ];
    }
}
