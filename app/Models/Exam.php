<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    const STATE_DRAFT = 'draft';

    const STATE_ONGOING = 'ongoing';

    const STATE_DONE = 'done';

    const STATE_CANCEL = 'cancel';

    protected $fillable = [
        'name', 'exam_type_id', 'academic_year_id', 'term_id', 'batch_id',
        'date_start', 'date_end', 'state', 'created_by', 'updated_by',
    ];

    protected $casts = ['date_start' => 'date', 'date_end' => 'date'];

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function schedules()
    {
        return $this->hasMany(ExamSchedule::class);
    }

    public function marksheets()
    {
        return $this->hasMany(Marksheet::class);
    }

    public function roomAllocations()
    {
        return $this->hasMany(ExamRoomAllocation::class);
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }
}
