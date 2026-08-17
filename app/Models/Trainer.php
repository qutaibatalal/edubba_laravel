<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = ['name', 'phone', 'email', 'specialization', 'faculty_id', 'hourly_rate', 'active'];

    protected $casts = ['hourly_rate' => 'float', 'active' => 'boolean'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
