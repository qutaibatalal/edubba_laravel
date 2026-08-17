<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceLine extends Model
{
    protected $fillable = ['invoice_id', 'description', 'qty', 'unit_price', 'amount'];

    protected $casts = ['qty' => 'float', 'unit_price' => 'float', 'amount' => 'float'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
