<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    const STATE_REGISTERED = 'registered';

    const STATE_ATTENDED = 'attended';

    const STATE_CANCELLED = 'cancelled';

    protected $fillable = ['event_id', 'student_id', 'name', 'phone', 'state'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
