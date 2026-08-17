<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingPayment extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_DONE = 'done';

    const STATE_CANCELLED = 'cancelled';

    protected $fillable = ['training_enrollment_id', 'student_id', 'date', 'amount', 'method', 'state'];

    protected $casts = ['date' => 'date', 'amount' => 'float'];

    public function enrollment()
    {
        return $this->belongsTo(TrainingEnrollment::class, 'training_enrollment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
