<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    const SENDER_STUDENT = 'student';

    const SENDER_FACULTY = 'faculty';

    protected $fillable = ['student_id', 'faculty_id', 'sender', 'body', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
