<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $fillable = ['name', 'program_id', 'batch_id', 'academic_year_id', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function lines()
    {
        return $this->hasMany(FeeLine::class);
    }
}
