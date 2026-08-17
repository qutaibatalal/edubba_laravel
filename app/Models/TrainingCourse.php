<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCourse extends Model
{
    protected $fillable = ['name', 'code', 'description', 'duration_hours', 'price', 'active'];

    protected $casts = ['duration_hours' => 'integer', 'price' => 'float', 'active' => 'boolean'];

    public function enrollments()
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    public function sessions()
    {
        return $this->hasMany(TrainingSession::class);
    }

    public function curriculums()
    {
        return $this->hasMany(TrainingCurriculum::class);
    }
}
