<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Block 5 — live GPS position reported by the driver app.
 * Used by GET /v1/parent/bus-tracking to render the fleet on a map.
 */
class BusLocation extends Model
{
    const UPDATED_AT = null;

    protected $fillable = ['vehicle_id', 'route_id', 'latitude', 'longitude', 'heading', 'speed', 'captured_at'];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'heading' => 'integer',
        'speed' => 'integer',
        'captured_at' => 'datetime',
    ];

    public function vehicle()
    {
        return $this->belongsTo(TransportVehicle::class, 'vehicle_id');
    }

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public static function latestFor(TransportVehicle $vehicle): ?self
    {
        return static::where('vehicle_id', $vehicle->id)->latest('captured_at')->first();
    }
}
