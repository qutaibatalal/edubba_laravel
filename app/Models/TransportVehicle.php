<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportVehicle extends Model
{
    protected $fillable = ['plate_number', 'model', 'capacity', 'driver_name', 'driver_phone', 'active'];

    protected $casts = ['capacity' => 'integer', 'active' => 'boolean'];

    public function routes()
    {
        return $this->hasMany(TransportRoute::class, 'vehicle_id');
    }
}
