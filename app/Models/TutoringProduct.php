<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoringProduct extends Model
{
    protected $fillable = ['name', 'code', 'price', 'active'];

    protected $casts = ['price' => 'float', 'active' => 'boolean'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'product_id');
    }
}
