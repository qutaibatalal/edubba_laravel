<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

class ApiUser extends Model
{
    use HasApiTokens;

    const ROLE_STUDENT = 'student';

    const ROLE_PARENT = 'parent';

    const ROLE_FACULTY = 'faculty';

    const ROLE_ADMIN = 'admin';

    protected $fillable = ['username', 'password', 'role', 'student_id', 'parent_id', 'faculty_id', 'active'];

    protected $hidden = ['password'];

    protected $casts = ['active' => 'boolean'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function linkedEntity()
    {
        return match ($this->role) {
            self::ROLE_STUDENT => $this->student,
            self::ROLE_PARENT => $this->parent,
            self::ROLE_FACULTY => $this->faculty,
            default => null,
        };
    }
}
