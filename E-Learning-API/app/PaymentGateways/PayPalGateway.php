<?php
namespace App\Services\Payments;

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;

class PayPalGateway implements PaymentGatewayInterface
{
    private PayPalHttpClient $client;
    public function __construct(
        string $mode, string $clientId, string $clientSecret, private readonly string $webhookId
    ) {
        $env = $mode === 'live'
            ? new ProductionEnvironment($clientId, $clientSecret)
            : new SandboxEnvironment($clientId, $clientSecret);
        $this->client = new PayPalHttpClient($env);
    }

    public function initiate(int $userId, int $amountMinor, string $currency, array $meta = []): array
    {
        $amount = number_format($amountMinor / 100, 2, '.', '');
        $req = new OrdersCreateRequest();
        $req->prefer('return=representation');
        $req->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => ['currency_code'=>strtoupper($currency), 'value'=>$amount],
                'reference_id' => 'user_'.$userId,
            ]],
            'application_context' => ['shipping_preference'=>'NO_SHIPPING']
        ];
        $res = $this->client->execute($req);
        $approve = collect($res->result->links)->firstWhere('rel','approve')->href ?? null;

        return [
            'external_id' => $res->result->id, // order id
            'approve_link' => $approve,
            'status' => $res->result->status,
            'raw' => json_decode(json_encode($res->result), true),
        ];
    }

    public function capture(string $externalId, array $payload = []): array
    {
        $req = new OrdersCaptureRequest($externalId);
        $req->prefer('return=representation');
        $res = $this->client->execute($req);

        $status = $res->result->status ?? 'UNKNOWN';
        return ['status'=>$status === 'COMPLETED' ? 'succeeded' : strtolower($status), 'raw'=>json_decode(json_encode($res->result), true)];
    }

    public function verifyWebhook(string $payload, array $headers): array
    {
        // In production, verify via PayPal’s Webhook signature verify API (Transmission-Id, Time, Sig, Cert-Url, Algo).
        // For brevity we return decoded event; add verification call in real app.
        return json_decode($payload, true);
    }

    public function interpretEvent(array $event): array
    {
        $type = $event['event_type'] ?? '';
        $resource = $event['resource'] ?? [];
        $externalId = $resource['id'] ?? null;

        return match ($type) {
            'CHECKOUT.ORDER.APPROVED' => ['status'=>'created','external_id'=>$externalId],
            'PAYMENT.CAPTURE.COMPLETED' => ['status'=>'succeeded','external_id'=>$resource['supplementary_data']['related_ids']['order_id'] ?? $externalId],
            'PAYMENT.CAPTURE.DENIED','PAYMENT.CAPTURE.REFUNDED','PAYMENT.CAPTURE.REVERSED' => ['status'=>'failed','external_id'=>$externalId],
            default => ['status'=>'ignored','external_id'=>$externalId],
        };
    }
}
