<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionRenewal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    /**
     * draft -> active
     */
    public static function activate(Subscription $subscription): Subscription
    {
        if ($subscription->state !== Subscription::STATE_DRAFT) {
            throw new \DomainException("Cannot activate subscription in state '{$subscription->state}'.");
        }

        $subscription->state = Subscription::STATE_ACTIVE;
        $subscription->start_date = $subscription->start_date ?? now()->toDateString();
        $subscription->next_renewal_date = $subscription->next_renewal_date ?? self::nextRenewalDate($subscription);
        $subscription->save();

        return $subscription;
    }

    /**
     * active -> paused
     */
    public static function pause(Subscription $subscription): Subscription
    {
        if ($subscription->state !== Subscription::STATE_ACTIVE) {
            throw new \DomainException("Cannot pause subscription in state '{$subscription->state}'.");
        }

        $subscription->state = Subscription::STATE_PAUSED;
        $subscription->save();

        return $subscription;
    }

    /**
     * paused -> active
     */
    public static function resume(Subscription $subscription): Subscription
    {
        if ($subscription->state !== Subscription::STATE_PAUSED) {
            throw new \DomainException("Cannot resume subscription in state '{$subscription->state}'.");
        }

        $subscription->state = Subscription::STATE_ACTIVE;
        $subscription->next_renewal_date = $subscription->next_renewal_date ?? self::nextRenewalDate($subscription);
        $subscription->save();

        return $subscription;
    }

    /**
     * active/paused -> expired (when sessions exhausted or date passed)
     */
    public static function expire(Subscription $subscription): Subscription
    {
        if (! in_array($subscription->state, [Subscription::STATE_ACTIVE, Subscription::STATE_PAUSED])) {
            throw new \DomainException("Cannot expire subscription in state '{$subscription->state}'.");
        }

        $subscription->state = Subscription::STATE_EXPIRED;
        $subscription->save();

        return $subscription;
    }

    /**
     * any -> cancelled
     */
    public static function cancel(Subscription $subscription): Subscription
    {
        if (in_array($subscription->state, [Subscription::STATE_CANCELLED, Subscription::STATE_EXPIRED])) {
            throw new \DomainException("Subscription already in state '{$subscription->state}'.");
        }

        $subscription->state = Subscription::STATE_CANCELLED;
        $subscription->save();

        return $subscription;
    }

    /**
     * Create a renewal record for the next period.
     */
    public static function renew(Subscription $subscription): SubscriptionRenewal
    {
        if ($subscription->state !== Subscription::STATE_ACTIVE) {
            throw new \DomainException('Cannot renew a non-active subscription.');
        }

        return DB::transaction(function () use ($subscription) {
            $renewal = SubscriptionRenewal::create([
                'subscription_id' => $subscription->id,
                'renewal_date' => $subscription->next_renewal_date ?? now()->toDateString(),
                'amount' => $subscription->amount,
                'state' => SubscriptionRenewal::STATE_PENDING,
            ]);

            $subscription->next_renewal_date = self::nextRenewalDate($subscription);
            $subscription->save();

            return $renewal;
        });
    }

    /**
     * Record that one session has been consumed; expire at zero remaining.
     */
    public static function consumeSession(Subscription $subscription): Subscription
    {
        if ($subscription->state !== Subscription::STATE_ACTIVE) {
            throw new \DomainException('Cannot consume sessions from a non-active subscription.');
        }

        $subscription->increment('sessions_used');

        if ($subscription->sessions_count > 0 && $subscription->sessions_used >= $subscription->sessions_count) {
            self::expire($subscription);
        }

        return $subscription->fresh();
    }

    protected static function nextRenewalDate(Subscription $subscription): string
    {
        $base = $subscription->start_date ?? now()->toDateString();
        $date = Carbon::parse($base);

        return $subscription->frequency === 'monthly' ? $date->addMonth()->toDateString() : $date->addWeek()->toDateString();
    }
}
