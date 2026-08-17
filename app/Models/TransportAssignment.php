<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportAssignment extends Model
{
    const STATE_ACTIVE = 'active';

    const STATE_ENDED = 'ended';

    protected $fillable = ['student_id', 'route_id', 'stop_id', 'start_date', 'end_date', 'state'];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, 'route_id');
    }

    public function stop()
    {
        return $this->belongsTo(TransportStop::class, 'stop_id');
    }
}
