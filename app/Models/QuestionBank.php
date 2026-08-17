<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    const TYPE_MCQ = 'mcq';

    const TYPE_ESSAY = 'essay';

    const TYPE_SHORT = 'short';

    protected $fillable = [
        'subject_id', 'course_id', 'question', 'type', 'answer', 'options',
        'marks', 'difficulty',
    ];

    protected $casts = ['options' => 'array', 'marks' => 'integer'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
