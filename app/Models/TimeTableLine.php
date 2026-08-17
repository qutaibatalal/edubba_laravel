<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeTableLine extends Model
{
    protected $fillable = [
        'time_table_id', 'week_day_id', 'timing_id', 'subject_id', 'faculty_id',
        'course_id', 'classroom_id',
    ];

    public function timeTable()
    {
        return $this->belongsTo(TimeTable::class);
    }

    public function weekDay()
    {
        return $this->belongsTo(WeekDay::class);
    }

    public function timing()
    {
        return $this->belongsTo(Timing::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
