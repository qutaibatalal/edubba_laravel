<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDailyAttendanceRegister extends Model
{
    protected $fillable = ['batch_id', 'academic_year_id', 'date', 'present_count', 'absent_count', 'total_count', 'details'];

    protected $casts = ['date' => 'date', 'details' => 'array'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
