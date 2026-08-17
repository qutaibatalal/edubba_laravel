<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingAttendance extends Model
{
    const STATUS_PRESENT = 'present';

    const STATUS_ABSENT = 'absent';

    const STATUS_LATE = 'late';

    protected $fillable = ['training_session_id', 'student_id', 'status'];

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'training_session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
