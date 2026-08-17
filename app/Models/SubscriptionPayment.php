<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_DONE = 'done';

    const STATE_CANCELLED = 'cancelled';

    protected $fillable = ['subscription_id', 'date', 'amount', 'method', 'transaction_id', 'state'];

    protected $casts = ['date' => 'date', 'amount' => 'float'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
