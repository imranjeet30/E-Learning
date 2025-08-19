<?php
namespace App\Services;

use App\Repositories\SubscriptionRepository;
use Carbon\Carbon;

class SubscriptionService
{
    public function __construct(private readonly SubscriptionRepository $subs) {}

    public function activate(int $userId, int $courseId): mixed
    {
        return $this->subs->create([
            'user_id'=>$userId,
            'course_id'=>$courseId,
            'start_date'=>Carbon::now(),
            'end_date'=>Carbon::now()->addMonth(),
            'status'=>'active'
        ]);
    }
}
