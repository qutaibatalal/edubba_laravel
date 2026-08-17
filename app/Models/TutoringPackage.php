<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoringPackage extends Model
{
    protected $fillable = ['name', 'sessions', 'price', 'active'];

    protected $casts = ['sessions' => 'integer', 'price' => 'float', 'active' => 'boolean'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'package_id');
    }
}
