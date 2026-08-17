<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinistryQuestion extends Model
{
    protected $table = 'ministry_questions';

    protected $fillable = [
        'subject_id', 'academic_year_id', 'stage', 'question_type',
        'question', 'options', 'answer', 'marks', 'year', 'session',
    ];

    protected $casts = [
        'options' => 'array',
        'marks' => 'integer',
        'year' => 'integer',
        'session' => 'integer',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
