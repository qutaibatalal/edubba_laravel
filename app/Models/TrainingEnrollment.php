<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingEnrollment extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_CONFIRMED = 'confirmed';

    const STATE_DONE = 'done';

    const STATE_CANCEL = 'cancel';

    protected $fillable = ['training_course_id', 'student_id', 'participant_id', 'enroll_date', 'state', 'amount_paid'];

    protected $casts = ['enroll_date' => 'date', 'amount_paid' => 'float'];

    public function trainingCourse()
    {
        return $this->belongsTo(TrainingCourse::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function certificate()
    {
        return $this->hasOne(TrainingCertificate::class);
    }
}
