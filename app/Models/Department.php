<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'description', 'head_faculty_id', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function headFaculty()
    {
        return $this->belongsTo(Faculty::class, 'head_faculty_id');
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }

    public function faculties()
    {
        return $this->hasMany(Faculty::class);
    }
}
