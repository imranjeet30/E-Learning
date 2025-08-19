<?php
namespace App\Repositories;

use App\Models\Payment;

class PaymentRepository
{
    public function create(array $data): Payment { return Payment::create($data); }
    public function findByExternal(string $externalId): ?Payment { return Payment::where('external_id',$externalId)->first(); }
    public function markSucceeded(Payment $p, ?string $txnId=null): Payment { $p->update(['status'=>'succeeded','transaction_id'=>$txnId]); return $p; }
    public function markFailed(Payment $p, string $reason=''): Payment { $p->update(['status'=>'failed','meta'=>array_merge($p->meta ?? [],['reason'=>$reason])]); return $p; }
}
