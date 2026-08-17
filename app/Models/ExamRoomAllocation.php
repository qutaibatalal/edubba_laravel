<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamRoomAllocation extends Model
{
    protected $fillable = [
        'exam_id', 'exam_schedule_id', 'exam_room_id', 'student_id', 'seat_no', 'attended',
    ];

    protected $casts = ['attended' => 'boolean'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function examSchedule()
    {
        return $this->belongsTo(ExamSchedule::class);
    }

    public function examRoom()
    {
        return $this->belongsTo(ExamRoom::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
