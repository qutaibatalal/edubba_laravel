<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingModule extends Model
{
    protected $fillable = ['training_curriculum_id', 'name', 'duration_hours', 'sequence'];

    protected $casts = ['duration_hours' => 'integer', 'sequence' => 'integer'];

    public function curriculum()
    {
        return $this->belongsTo(TrainingCurriculum::class, 'training_curriculum_id');
    }
}
