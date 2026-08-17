<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{
    protected $table = 'class_sessions';

    const STATE_PLANNED = 'planned';

    const STATE_DONE = 'done';

    const STATE_CANCELLED = 'cancelled';

    protected $fillable = [
        'time_table_line_id', 'batch_id', 'course_id', 'subject_id', 'faculty_id',
        'classroom_id', 'date', 'start_time', 'end_time', 'state', 'topic', 'notes',
    ];

    protected $casts = ['date' => 'date'];

    public function timeTableLine()
    {
        return $this->belongsTo(TimeTableLine::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function attendanceSheet()
    {
        return $this->hasOne(AttendanceSheet::class, 'session_id');
    }
}
