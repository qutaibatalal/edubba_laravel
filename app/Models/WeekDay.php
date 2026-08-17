<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeekDay extends Model
{
    protected $fillable = ['name', 'sequence', 'active'];

    protected $casts = ['sequence' => 'integer', 'active' => 'boolean'];
}
