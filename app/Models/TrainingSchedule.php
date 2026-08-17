<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingSchedule extends Model
{
    const STATE_PLANNED = 'planned';

    const STATE_COMPLETED = 'completed';

    const STATE_CANCELLED = 'cancelled';

    protected $fillable = ['training_course_id', 'trainer_id', 'venue_id', 'start_date', 'end_date', 'state'];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

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
}
