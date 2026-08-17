<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudyGroup extends Model
{
    protected $fillable = [
        'name', 'subject_id', 'tutor_id', 'center_id', 'max_students', 'level', 'state',
    ];

    protected $casts = ['max_students' => 'integer'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'study_group_students')
            ->withPivot(['join_date', 'state']);
    }

    public function sessions()
    {
        return $this->hasMany(StudyGroupSession::class);
    }
}
