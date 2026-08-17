<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamSchedule extends Model
{
    protected $fillable = [
        'exam_id', 'subject_id', 'course_id', 'date', 'start_time', 'end_time',
        'max_marks', 'pass_marks',
    ];

    protected $casts = ['date' => 'date', 'max_marks' => 'float', 'pass_marks' => 'float'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
