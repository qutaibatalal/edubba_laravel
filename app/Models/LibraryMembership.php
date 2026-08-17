<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryMembership extends Model
{
    const STATE_ACTIVE = 'active';

    const STATE_EXPIRED = 'expired';

    protected $fillable = ['student_id', 'start_date', 'end_date', 'state'];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
