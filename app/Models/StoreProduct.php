<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreProduct extends Model
{
    protected $fillable = ['category_id', 'name', 'code', 'stock_qty', 'unit_price', 'active'];

    protected $casts = ['stock_qty' => 'integer', 'unit_price' => 'float', 'active' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(StoreCategory::class);
    }

    public function stocks()
    {
        return $this->hasMany(StoreStock::class);
    }
}
