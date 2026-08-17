<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostelAllocation extends Model
{
    const STATE_ACTIVE = 'active';

    const STATE_ENDED = 'ended';

    protected $fillable = ['room_id', 'student_id', 'start_date', 'end_date', 'state'];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date'];

    public function room()
    {
        return $this->belongsTo(HostelRoom::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
