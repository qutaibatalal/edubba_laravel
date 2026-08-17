<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentReminder extends Model
{
    const STATE_PENDING = 'pending';

    const STATE_SENT = 'sent';

    const STATE_FAILED = 'failed';

    protected $fillable = ['subscription_id', 'remind_date', 'channel', 'state'];

    protected $casts = ['remind_date' => 'date'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
