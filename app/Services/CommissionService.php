<?php

namespace App\Services;

use App\Models\Commission;
use App\Models\CommissionLine;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class CommissionService
{
    /**
     * Calculate commission for a paid subscription (rate x base) and mark calculated.
     */
    public static function calculateForSubscription(Subscription $subscription, float $rate, ?int $tutorId = null, ?int $agentId = null): Commission
    {
        return DB::transaction(function () use ($subscription, $rate, $tutorId, $agentId) {
            $commission = Commission::create([
                'reference' => SequenceService::next('commission', 'COM'),
                'tutor_id' => $tutorId ?? $subscription->tutor_id,
                'agent_id' => $agentId,
                'base_amount' => $subscription->amount,
                'rate' => $rate,
                'amount' => round($subscription->amount * $rate / 100, 2),
                'state' => Commission::STATE_CALCULATED,
            ]);

            CommissionLine::create([
                'commission_id' => $commission->id,
                'subscription_id' => $subscription->id,
                'amount' => $commission->amount,
            ]);

            return $commission;
        });
    }

    /**
     * Mark a commission as paid.
     */
    public static function markPaid(Commission $commission): Commission
    {
        if ($commission->state !== Commission::STATE_CALCULATED) {
            throw new \DomainException("Cannot mark commission in state '{$commission->state}' as paid.");
        }

        $commission->state = Commission::STATE_PAID;
        $commission->save();

        return $commission;
    }
}
