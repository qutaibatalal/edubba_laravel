<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeTable extends Model
{
    protected $fillable = ['batch_id', 'academic_year_id', 'term_id', 'name', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function term()
    {
        return $this->belongsTo(Term::class);
    }

    public function lines()
    {
        return $this->hasMany(TimeTableLine::class);
    }
}
