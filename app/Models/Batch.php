<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'program_id', 'academic_year_id', 'class_teacher_id', 'capacity', 'active'];

    protected $casts = ['capacity' => 'integer', 'active' => 'boolean'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function classTeacher()
    {
        return $this->belongsTo(Faculty::class, 'class_teacher_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class);
    }
}
