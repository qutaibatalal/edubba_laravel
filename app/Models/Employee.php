<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    const STATE_DRAFT = 'draft';

    const STATE_JOINED = 'joined';

    const STATE_LEFT = 'left';

    protected $fillable = [
        'employee_code', 'name', 'gender', 'birth_date', 'phone', 'email', 'address',
        'job_title', 'department', 'join_date', 'leaving_date', 'salary', 'state', 'active',
    ];

    protected $casts = ['birth_date' => 'date', 'join_date' => 'date', 'leaving_date' => 'date', 'salary' => 'float', 'active' => 'boolean'];
}
