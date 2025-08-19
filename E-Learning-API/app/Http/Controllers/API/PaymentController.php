<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $payments,
        private readonly SubscriptionService $subs
    ) {}

    public function initiate(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'gateway'   => 'required|in:stripe,razorpay,paypal',
            'amount'    => 'required|numeric|min:1'
        ]);

        $amountMinor = (int) round($data['amount'] * 100);
        $res = $this->payments->initiate(
            userId: $request->user()->id,
            amountMinor: $amountMinor,
            currency: config('payments.currency'),
            gateway: $data['gateway'],
            meta: ['course_id'=>$data['course_id']]
        );

        return response()->json($res, 201);
    }

    public function capture(Request $request)
    {
        $data = $request->validate([
            'gateway'     => 'required|in:stripe,paypal,razorpay',
            'external_id' => 'required|string',    // pi_xxx | order_xxx | PAYPAL ORDER ID
            'course_id'   => 'nullable|exists:courses,id'
        ]);

        $res = $this->payments->capture($data['gateway'], $data['external_id']);

        // Optionally activate immediately if succeeded and course_id supplied:
        if (($res['status'] ?? '') === 'succeeded' && !empty($data['course_id'])) {
            $sub = $this->subs->activate($request->user()->id, (int)$data['course_id']);
            return response()->json(['capture'=>$res,'subscription'=>$sub]);
        }
        return response()->json(['capture'=>$res]);
    }
}
