<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExcuse extends Model
{
    const STATE_PENDING = 'pending';

    const STATE_APPROVED = 'approved';

    const STATE_REJECTED = 'rejected';

    protected $fillable = ['student_id', 'date', 'reason', 'document', 'state', 'approved_by'];

    protected $casts = ['date' => 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
