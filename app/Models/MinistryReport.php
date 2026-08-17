<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinistryReport extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_GENERATED = 'generated';

    const STATE_SUBMITTED = 'submitted';

    protected $fillable = ['name', 'academic_year_id', 'term_id', 'report_type', 'data', 'state'];

    protected $casts = ['data' => 'array'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }
}
