<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WebhookController;

Route::prefix('v1')->group(function () {

    // ✅ Authentication
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/webhooks/{gateway}', [WebhookController::class,'handle']); // {gateway}=stripe|razorpay|paypal

    Route::middleware('auth:sanctum')->group(function () {

        // ✅ Courses
        Route::get('/courses', [CourseController::class, 'index']);
        Route::get('/courses/{id}', [CourseController::class, 'show']);
        Route::post('/courses', [CourseController::class, 'store']);   // Only admin/teacher
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

        // ✅ Subscriptions
        Route::post('/subscriptions', [SubscriptionController::class, 'subscribe']);
        Route::get('/subscriptions', [SubscriptionController::class, 'mySubscriptions']);

        // ✅ Payments
        Route::post('/payments/initiate', [PaymentController::class, 'initiate']);
        Route::post('/payments/verify', [PaymentController::class, 'verify']);
    });
});
