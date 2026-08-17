<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyGroupStudent extends Model
{
    protected $table = 'study_group_students';

    protected $fillable = ['study_group_id', 'student_id', 'join_date', 'state'];

    protected $casts = ['join_date' => 'date'];

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
