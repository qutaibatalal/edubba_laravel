<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentBadge extends Model
{
    public $timestamps = false;

    protected $fillable = ['student_id', 'badge_type', 'badge_name', 'earned_date'];

    protected $casts = ['earned_date' => 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
