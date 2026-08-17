<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_DONE = 'done';

    protected $fillable = ['name', 'student_id', 'tutor_id', 'subject_id', 'date', 'max_marks', 'state'];

    protected $casts = ['date' => 'date', 'max_marks' => 'float'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function results()
    {
        return $this->hasMany(AssessmentResult::class);
    }
}
