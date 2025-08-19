<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->subscriptions()->with('course')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $subscription = Subscription::create([
            'user_id'   => $request->user()->id,
            'course_id' => $request->course_id,
            'status'    => 'pending',
        ]);

        return response()->json($subscription, 201);
    }

    public function show($id)
    {
        return Subscription::with('course')->findOrFail($id);
    }

    public function cancel($id)
    {
        $subscription = Subscription::findOrFail($id);
        $subscription->status = 'cancelled';
        $subscription->save();

        return response()->json(['message' => 'Subscription cancelled']);
    }
}
