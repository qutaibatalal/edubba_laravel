<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorAvailability extends Model
{
    protected $fillable = ['tutor_id', 'week_day_id', 'start_time', 'end_time'];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function weekDay()
    {
        return $this->belongsTo(WeekDay::class);
    }
}
