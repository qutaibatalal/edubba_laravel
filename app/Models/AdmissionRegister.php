<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionRegister extends Model
{
    protected $fillable = [
        'name', 'academic_year_id', 'batch_id', 'start_date', 'end_date', 'active',
    ];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'active' => 'boolean'];

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class, 'register_id');
    }
}
