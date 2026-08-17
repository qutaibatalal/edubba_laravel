<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyGroupSession extends Model
{
    const STATE_SCHEDULED = 'scheduled';

    const STATE_DONE = 'done';

    const STATE_CANCELLED = 'cancelled';

    protected $fillable = ['study_group_id', 'tutor_id', 'date', 'start_time', 'end_time', 'state', 'notes'];

    protected $casts = ['date' => 'date'];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function attendances()
    {
        return $this->hasMany(StudyGroupAttendance::class);
    }
}
