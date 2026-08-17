<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'department_id', 'duration_years', 'description', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
