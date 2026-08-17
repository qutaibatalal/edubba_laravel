<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    const STATE_OPEN = 'open';

    const STATE_IN_PROGRESS = 'in_progress';

    const STATE_RESOLVED = 'resolved';

    const STATE_CLOSED = 'closed';

    protected $fillable = ['student_id', 'subject', 'description', 'priority', 'state', 'assigned_to'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
