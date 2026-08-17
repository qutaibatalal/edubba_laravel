<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostelRoom extends Model
{
    const STATE_AVAILABLE = 'available';

    const STATE_FULL = 'full';

    const STATE_MAINTENANCE = 'maintenance';

    protected $fillable = ['hostel_id', 'room_no', 'capacity', 'occupied', 'monthly_rent', 'state'];

    protected $casts = ['capacity' => 'integer', 'occupied' => 'integer', 'monthly_rent' => 'float'];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }

    public function allocations()
    {
        return $this->hasMany(HostelAllocation::class);
    }
}
