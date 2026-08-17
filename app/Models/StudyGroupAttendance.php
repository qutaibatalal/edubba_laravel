<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyGroupAttendance extends Model
{
    const STATUS_PRESENT = 'present';

    const STATUS_ABSENT = 'absent';

    const STATUS_LATE = 'late';

    protected $fillable = ['study_group_session_id', 'student_id', 'status'];

    public function session()
    {
        return $this->belongsTo(StudyGroupSession::class, 'study_group_session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
