<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'subject_id', 'program_id', 'batch_id', 'academic_year_id',
        'faculty_id', 'credit_hours', 'syllabus', 'active',
    ];

    protected $casts = ['credit_hours' => 'integer', 'active' => 'boolean'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_course')
            ->withPivot(['batch_id', 'academic_year_id', 'state', 'total_fees'])
            ->withTimestamps();
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class);
    }
}
