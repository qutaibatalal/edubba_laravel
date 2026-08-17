<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IraqiCalendar extends Model
{
    public $timestamps = false;

    protected $fillable = ['gregorian_date', 'hijri_date', 'iraqi_name', 'is_holiday', 'description'];

    protected $casts = ['gregorian_date' => 'date', 'is_holiday' => 'boolean'];
}
