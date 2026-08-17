<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sequence extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'prefix', 'next', 'padding'];

    protected $casts = ['next' => 'integer', 'padding' => 'integer'];
}
