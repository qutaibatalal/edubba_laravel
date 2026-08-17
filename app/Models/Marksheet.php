<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marksheet extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_DONE = 'done';

    const RESULT_PASS = 'pass';

    const RESULT_FAIL = 'fail';

    protected $fillable = [
        'exam_id', 'student_id', 'batch_id', 'total_marks', 'obtained_marks',
        'percentage', 'grade', 'result', 'rank', 'state',
    ];

    protected $casts = [
        'total_marks' => 'float',
        'obtained_marks' => 'float',
        'percentage' => 'float',
        'rank' => 'integer',
        'finalized_at' => 'datetime',
    ];

    public function getIsFinalizedAttribute()
    {
        return $this->state === self::STATE_DONE;
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function lines()
    {
        return $this->hasMany(MarksheetLine::class);
    }
}
