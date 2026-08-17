<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoringSessionFeedback extends Model
{
    protected $table = 'tutoring_session_feedbacks';

    protected $fillable = ['study_group_session_id', 'student_id', 'rating', 'comment'];

    protected $casts = ['rating' => 'integer'];

    public function session()
    {
        return $this->belongsTo(StudyGroupSession::class, 'study_group_session_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
