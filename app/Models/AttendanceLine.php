<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLine extends Model
{
    const STATUS_PRESENT = 'present';

    const STATUS_ABSENT = 'absent';

    const STATUS_LATE = 'late';

    const STATUS_LEAVE = 'leave';

    public $timestamps = false;

    protected $fillable = ['attendance_sheet_id', 'student_id', 'status', 'note'];

    public function sheet()
    {
        return $this->belongsTo(AttendanceSheet::class, 'attendance_sheet_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
