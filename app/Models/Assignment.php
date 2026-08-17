<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_PUBLISHED = 'published';

    protected $fillable = ['course_id', 'faculty_id', 'title', 'description', 'due_date', 'state'];

    protected $casts = ['due_date' => 'date'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function submissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }
}
