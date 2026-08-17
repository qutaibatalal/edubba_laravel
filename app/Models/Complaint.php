<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    const STATE_OPEN = 'open';

    const STATE_IN_PROGRESS = 'in_progress';

    const STATE_RESOLVED = 'resolved';

    const STATE_CLOSED = 'closed';

    protected $fillable = ['category_id', 'student_id', 'subject', 'description', 'state', 'assigned_to'];

    public function category()
    {
        return $this->belongsTo(ComplaintCategory::class, 'category_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
