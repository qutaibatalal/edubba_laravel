<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssessmentResult extends Model
{
    protected $fillable = ['assessment_id', 'student_id', 'marks', 'grade', 'feedback'];

    protected $casts = ['marks' => 'float'];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
