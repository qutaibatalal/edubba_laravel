<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionRenewal extends Model
{
    const STATE_PENDING = 'pending';

    const STATE_DONE = 'done';

    const STATE_SKIPPED = 'skipped';

    protected $fillable = ['subscription_id', 'renewal_date', 'amount', 'state', 'notes'];

    protected $casts = ['renewal_date' => 'date', 'amount' => 'float'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
