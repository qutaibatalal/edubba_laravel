<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCourse extends Model
{
    protected $table = 'student_course';

    public $timestamps = true;

    protected $fillable = [
        'student_id', 'course_id', 'batch_id', 'academic_year_id', 'state', 'total_fees',
    ];

    protected $casts = ['total_fees' => 'float'];

    const STATE_RUNNING = 'running';

    const STATE_DONE = 'done';

    const STATE_CANCEL = 'cancel';

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
