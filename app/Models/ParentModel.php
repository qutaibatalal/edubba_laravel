<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentModel extends Model
{
    use SoftDeletes;

    protected $table = 'parents';

    protected $fillable = [
        'name', 'phone', 'mobile', 'email', 'national_id', 'address',
        'occupation', 'relation', 'photo', 'active',
    ];

    protected $casts = ['active' => 'boolean'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_parent', 'parent_id', 'student_id')
            ->withPivot(['relation', 'is_main', 'guardian', 'emergency_contact']);
    }

    public function children()
    {
        return $this->students();
    }
}
