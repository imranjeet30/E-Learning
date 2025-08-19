<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    // app/Providers/AppServiceProvider.php (or dedicated PaymentsServiceProvider)
public function register()
{
    // AppServiceProvider@register
    $this->app->singleton(\App\Services\Payments\GatewayFactory::class, fn() => new \App\Services\Payments\GatewayFactory());

    $this->app->bind(\App\Services\Payments\PaymentGatewayInterface::class, function($app) {
        $conf = config('payments');
        $default = $conf['default'];
        return match ($default) {
            'stripe' => new \App\Services\Payments\StripeGateway(
                new \Stripe\StripeClient($conf['stripe']['secret']),
                $conf['stripe']['webhook_secret']
            ),
            'razorpay' => new \App\Services\Payments\RazorpayGateway(
                $conf['razorpay']['key'],
                $conf['razorpay']['secret'],
                $conf['razorpay']['webhook_secret']
            ),
            'paypal' => new \App\Services\Payments\PayPalGateway(
                $conf['paypal']['mode'],
                $conf['paypal']['client_id'],
                $conf['paypal']['client_secret'],
                $conf['paypal']['webhook_id']
            ),
            default => throw new \InvalidArgumentException('Unknown default gateway')
        };
    });
}


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
