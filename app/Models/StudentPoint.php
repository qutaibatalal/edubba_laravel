<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPoint extends Model
{
    public $timestamps = false;

    protected $fillable = ['student_id', 'points', 'reason', 'reference_type', 'reference_id', 'earned_at'];

    protected $casts = [
        'points' => 'integer',
        'earned_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
