<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingMaterial extends Model
{
    protected $fillable = ['training_course_id', 'module_id', 'title', 'file', 'description'];

    public function trainingCourse()
    {
        return $this->belongsTo(TrainingCourse::class);
    }

    public function module()
    {
        return $this->belongsTo(TrainingModule::class, 'module_id');
    }
}
