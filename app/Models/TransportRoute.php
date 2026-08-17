<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    protected $fillable = ['name', 'vehicle_id', 'description', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function vehicle()
    {
        return $this->belongsTo(TransportVehicle::class, 'vehicle_id');
    }

    public function stops()
    {
        return $this->hasMany(TransportStop::class, 'route_id');
    }

    public function assignments()
    {
        return $this->hasMany(TransportAssignment::class, 'route_id');
    }
}
