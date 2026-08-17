<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingVenue extends Model
{
    protected $fillable = ['name', 'address', 'capacity', 'active'];

    protected $casts = ['capacity' => 'integer', 'active' => 'boolean'];
}
