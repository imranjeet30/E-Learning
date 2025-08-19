<?php
namespace App\Repositories;

use App\Models\Subscription;

class SubscriptionRepository
{
    public function create(array $data): Subscription { return Subscription::create($data); }
}
