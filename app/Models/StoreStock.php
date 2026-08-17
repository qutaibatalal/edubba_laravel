<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreStock extends Model
{
    const TYPE_IN = 'in';

    const TYPE_OUT = 'out';

    protected $fillable = ['product_id', 'qty', 'type', 'date', 'note'];

    protected $casts = ['qty' => 'integer', 'date' => 'date'];

    public function product()
    {
        return $this->belongsTo(StoreProduct::class);
    }
}
