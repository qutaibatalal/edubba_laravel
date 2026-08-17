<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionLine extends Model
{
    protected $fillable = ['commission_id', 'subscription_id', 'amount'];

    protected $casts = ['amount' => 'float'];

    public function commission()
    {
        return $this->belongsTo(Commission::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}
