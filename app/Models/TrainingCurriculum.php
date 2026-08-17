<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCurriculum extends Model
{
    protected $table = 'training_curriculums';

    protected $fillable = ['training_course_id', 'name', 'description'];

    public function trainingCourse()
    {
        return $this->belongsTo(TrainingCourse::class);
    }

    public function modules()
    {
        return $this->hasMany(TrainingModule::class);
    }
}
