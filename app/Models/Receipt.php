<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    protected $fillable = ['receipt_no', 'payment_id', 'invoice_id', 'date', 'amount', 'document'];

    protected $casts = ['date' => 'date', 'amount' => 'float'];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
