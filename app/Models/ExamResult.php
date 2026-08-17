<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamResult extends Model
{
    const RESULT_PASS = 'pass';

    const RESULT_FAIL = 'fail';

    protected $fillable = [
        'student_id', 'exam_id', 'term_id', 'academic_year_id', 'batch_id',
        'total', 'average', 'grade', 'rank', 'result', 'published_at',
    ];

    protected $casts = [
        'total' => 'float',
        'average' => 'float',
        'rank' => 'integer',
        'published_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}
