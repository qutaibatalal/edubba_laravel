<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['name', 'date_start', 'date_stop', 'academic_year_id', 'active'];

    protected $casts = ['date_start' => 'date', 'date_stop' => 'date', 'active' => 'boolean'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
