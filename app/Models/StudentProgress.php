<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProgress extends Model
{
    protected $table = 'student_progresses';

    protected $fillable = ['student_id', 'subject_id', 'score', 'level', 'notes', 'recorded_on'];

    protected $casts = ['score' => 'float', 'recorded_on' => 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
