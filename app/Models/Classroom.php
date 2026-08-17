<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'building', 'floor', 'capacity', 'active'];

    protected $casts = ['floor' => 'integer', 'capacity' => 'integer', 'active' => 'boolean'];
}
