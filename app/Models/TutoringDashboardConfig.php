<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoringDashboardConfig extends Model
{
    protected $fillable = ['name', 'config_key', 'config', 'active'];

    protected $casts = ['config' => 'array', 'active' => 'boolean'];
}
