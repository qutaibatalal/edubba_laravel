<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceSheet extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_DONE = 'done';

    protected $fillable = ['session_id', 'batch_id', 'course_id', 'faculty_id', 'date', 'state'];

    protected $casts = ['date' => 'date'];

    public function session()
    {
        return $this->belongsTo(ClassSession::class, 'session_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function lines()
    {
        return $this->hasMany(AttendanceLine::class);
    }
}
