<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faculty extends Model
{
    use Auditable, SoftDeletes;

    const STATE_DRAFT = 'draft';

    const STATE_JOINED = 'joined';

    const STATE_LEFT = 'left';

    protected $fillable = [
        'faculty_code', 'name', 'middle_name', 'last_name', 'birth_date', 'gender',
        'marital_status', 'national_id', 'phone', 'mobile', 'email', 'address',
        'qualification', 'specialization', 'join_date', 'leaving_date', 'department_id',
        'user_id', 'photo', 'state', 'active', 'created_by', 'updated_by',
    ];

    protected $casts = ['birth_date' => 'date', 'join_date' => 'date', 'leaving_date' => 'date', 'active' => 'boolean'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'faculty_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'class_teacher_id');
    }

    public function classSessions()
    {
        return $this->hasMany(ClassSession::class, 'faculty_id');
    }

    public function departmentsHeaded()
    {
        return $this->hasMany(Department::class, 'head_faculty_id');
    }

    public function hr()
    {
        return $this->hasOne(FacultyHr::class);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->name} {$this->middle_name} {$this->last_name}");
    }
}
