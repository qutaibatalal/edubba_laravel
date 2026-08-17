<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportStop extends Model
{
    protected $fillable = ['route_id', 'name', 'pickup_time', 'sequence'];

    protected $casts = ['sequence' => 'integer'];

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }
}
