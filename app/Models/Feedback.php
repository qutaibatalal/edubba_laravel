<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    const STATE_SUBMITTED = 'submitted';

    const STATE_REVIEWED = 'reviewed';

    protected $table = 'feedbacks';

    protected $fillable = ['form_id', 'student_id', 'rating', 'comment', 'state'];

    protected $casts = ['rating' => 'integer'];

    public function form()
    {
        return $this->belongsTo(FeedbackForm::class, 'form_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
