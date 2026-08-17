<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    const TYPE_CREDIT = 'credit';

    const TYPE_DEBIT = 'debit';

    protected $fillable = ['wallet_id', 'type', 'amount', 'reference', 'description'];

    protected $casts = ['amount' => 'float'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }
}
