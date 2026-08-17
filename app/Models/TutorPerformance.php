<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorPerformance extends Model
{
    protected $fillable = ['tutor_id', 'academic_year_id', 'sessions', 'students', 'rating', 'attendance_rate'];

    protected $casts = [
        'sessions' => 'integer',
        'students' => 'integer',
        'rating' => 'float',
        'attendance_rate' => 'float',
    ];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
