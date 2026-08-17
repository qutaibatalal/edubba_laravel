<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ['name', 'type', 'description', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function bookings()
    {
        return $this->hasMany(ResourceBooking::class);
    }
}
