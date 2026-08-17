<?php

namespace App\Jobs;

use App\Models\CommissionLine;
use App\Models\Subscription;
use App\Services\CommissionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CommissionPayoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Recalculate commissions for paid subscriptions that have not yet been commissioned.
        $subscriptions = Subscription::where('state', Subscription::STATE_ACTIVE)
            ->where('paid_amount', '>', 0)
            ->get();

        foreach ($subscriptions as $subscription) {
            $already = CommissionLine::whereHas('commission', fn ($q) => $q->where('tutor_id', $subscription->tutor_id))
                ->where('subscription_id', $subscription->id)
                ->exists();

            if (! $already && $subscription->tutor_id) {
                CommissionService::calculateForSubscription($subscription, 10, $subscription->tutor_id);
            }
        }
    }
}
