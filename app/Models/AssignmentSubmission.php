<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    const STATE_SUBMITTED = 'submitted';

    const STATE_GRADED = 'graded';

    protected $fillable = ['assignment_id', 'student_id', 'file', 'note', 'submitted_at', 'grade', 'feedback', 'state'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'grade' => 'float',
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
