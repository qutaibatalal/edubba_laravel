<?php

namespace App\Jobs;

use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionRenewalJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $due = Subscription::where('state', Subscription::STATE_ACTIVE)
            ->whereNotNull('next_renewal_date')
            ->where('next_renewal_date', '<=', now()->toDateString())
            ->get();

        foreach ($due as $subscription) {
            SubscriptionService::renew($subscription);
        }
    }
}
