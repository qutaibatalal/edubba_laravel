<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingAssessment extends Model
{
    protected $fillable = ['training_course_id', 'student_id', 'name', 'date', 'max_marks', 'marks', 'passed'];

    protected $casts = ['date' => 'date', 'max_marks' => 'float', 'marks' => 'float', 'passed' => 'boolean'];

    public function trainingCourse()
    {
        return $this->belongsTo(TrainingCourse::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
