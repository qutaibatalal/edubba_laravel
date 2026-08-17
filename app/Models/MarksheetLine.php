<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarksheetLine extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'marksheet_id', 'subject_id', 'course_id', 'max_marks', 'marks',
        'pass_marks', 'percentage', 'grade', 'passed',
    ];

    protected $casts = [
        'max_marks' => 'float',
        'marks' => 'float',
        'pass_marks' => 'float',
        'percentage' => 'float',
        'passed' => 'boolean',
    ];

    public function marksheet()
    {
        return $this->belongsTo(Marksheet::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
