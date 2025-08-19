<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(
        private readonly PaymentService $payments,
        private readonly SubscriptionService $subs
    ) {}

    public function handle(Request $request, string $gateway)
    {
        $payload = $request->getContent();
        $headers = $request->headers->all();

        try {
            $normalized = $this->payments->handleWebhook($gateway, $payload, $headers);

            if (($normalized['status'] ?? '') === 'succeeded') {
                // Extract courseId/userId if you stored in metadata:
                $body = json_decode($payload, true);
                $courseId = $body['data']['object']['metadata']['course_id'] ?? null;            // Stripe
                $courseId = $courseId ?? ($body['payload']['payment']['entity']['notes']['course_id'] ?? null); // Razorpay
                // PayPal: You can map from order custom fields or store external_id->course_id mapping in DB.

                // In a real app you'd look up the Payment by external_id and then user_id & course_id from meta
                // For demo, you may need to pass course_id in metadata on initiate and retrieve it here.

                if ($courseId) {
                    // You also need the user ID. Store user_id in metadata during initiate.
                    $userId = $body['data']['object']['metadata']['user_id'] ??
                              $body['payload']['payment']['entity']['notes']['user_id'] ?? null;

                    if ($userId) {
                        $this->subs->activate((int)$userId, (int)$courseId);
                    }
                }
            }
            return response()->json(['ok'=>true]);
        } catch (\Throwable $e) {
            Log::error('Webhook error: '.$e->getMessage());
            return response()->json(['ok'=>false,'error'=>$e->getMessage()], 400);
        }
    }
}
