<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    protected $fillable = ['name', 'weight', 'active'];

    protected $casts = ['weight' => 'float', 'active' => 'boolean'];

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
