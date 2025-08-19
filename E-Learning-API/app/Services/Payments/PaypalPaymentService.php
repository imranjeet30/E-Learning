<?php
namespace App\Services\Payments;

class PaypalPaymentService implements PaymentGatewayInterface
{
    public function charge(array $data)
    {
        // Example only – in real code use PayPal SDK
        return [
            'status' => 'success',
            'gateway' => 'paypal',
            'amount' => $data['amount'],
            'transaction_id' => 'paypal_' . uniqid()
        ];
    }
}
