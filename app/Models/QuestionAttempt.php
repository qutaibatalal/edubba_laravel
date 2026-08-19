<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionAttempt extends Model
{
    public $timestamps = false;

    protected $fillable = ['student_id', 'question_id', 'student_answer', 'is_correct', 'answered_at'];

    protected $casts = [
        'is_correct' => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function question()
    {
        return $this->belongsTo(MinistryQuestion::class, 'question_id');
    }
}
