<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['subject_type', 'subject_id', 'type', 'body', 'user_id', 'changes'];

    protected $casts = ['changes' => 'array'];

    public function subject()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
