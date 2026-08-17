<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    protected $table = 'alumni';

    protected $fillable = ['student_id', 'name', 'graduation_year', 'contact', 'note'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
