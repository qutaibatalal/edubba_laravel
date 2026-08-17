<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackResponse extends Model
{
    protected $fillable = ['feedback_form_id', 'student_id', 'answers'];

    protected $casts = ['answers' => 'array'];

    public function form()
    {
        return $this->belongsTo(FeedbackForm::class, 'feedback_form_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
