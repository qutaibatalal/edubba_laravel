<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResourceBooking extends Model
{
    const STATE_BOOKED = 'booked';

    const STATE_CANCELLED = 'cancelled';

    const STATE_COMPLETED = 'completed';

    protected $fillable = ['resource_id', 'student_id', 'date', 'start_time', 'end_time', 'state', 'notes'];

    protected $casts = ['date' => 'date'];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
