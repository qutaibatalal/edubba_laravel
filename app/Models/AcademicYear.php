<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicYear extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'date_start', 'date_stop', 'current', 'active', 'created_by', 'updated_by'];

    protected $casts = [
        'date_start' => 'date',
        'date_stop' => 'date',
        'current' => 'boolean',
        'active' => 'boolean',
    ];

    public function terms()
    {
        return $this->hasMany(Term::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function scopeCurrent($q)
    {
        return $q->where('current', true);
    }
}
