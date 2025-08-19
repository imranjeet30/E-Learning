<?php
namespace App\Services\Payments;

use Stripe\StripeClient;
use Razorpay\Api\Api;

class GatewayFactory
{
    public function make(string $gateway): PaymentGatewayInterface
    {
        $c = config('payments');
        return match ($gateway) {
            'stripe' => new StripeGateway(
                new StripeClient($c['stripe']['secret']),
                $c['stripe']['webhook_secret']
            ),
            'razorpay' => new RazorpayGateway(
                $c['razorpay']['key'], $c['razorpay']['secret'], $c['razorpay']['webhook_secret']
            ),
            'paypal' => new PayPalGateway(
                $c['paypal']['mode'], $c['paypal']['client_id'], $c['paypal']['client_secret'], $c['paypal']['webhook_id']
            ),
            default => throw new \InvalidArgumentException('Unknown gateway: '.$gateway),
        };
    }
}
