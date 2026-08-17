<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    const STATE_PLANNED = 'planned';

    const STATE_DONE = 'done';

    const STATE_CANCELLED = 'cancelled';

    protected $fillable = [
        'training_course_id', 'trainer_id', 'venue_id', 'schedule_id',
        'date', 'start_time', 'end_time', 'state', 'topic',
    ];

    protected $casts = ['date' => 'date'];

    public function trainingCourse()
    {
        return $this->belongsTo(TrainingCourse::class);
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    public function venue()
    {
        return $this->belongsTo(TrainingVenue::class, 'venue_id');
    }

    public function schedule()
    {
        return $this->belongsTo(TrainingSchedule::class, 'schedule_id');
    }

    public function attendances()
    {
        return $this->hasMany(TrainingAttendance::class);
    }
}
