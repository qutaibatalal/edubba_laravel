<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Term extends Model
{
    use SoftDeletes;

    protected $fillable = ['academic_year_id', 'name', 'date_start', 'date_stop', 'active'];

    protected $casts = ['date_start' => 'date', 'date_stop' => 'date', 'active' => 'boolean'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
