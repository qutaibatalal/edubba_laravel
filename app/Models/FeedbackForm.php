<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackForm extends Model
{
    protected $fillable = ['name', 'type', 'questions', 'active'];

    protected $casts = ['questions' => 'array', 'active' => 'boolean'];

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'form_id');
    }

    public function responses()
    {
        return $this->hasMany(FeedbackResponse::class);
    }
}
