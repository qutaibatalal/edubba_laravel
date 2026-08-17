<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeWaiver extends Model
{
    const STATE_PENDING = 'pending';

    const STATE_APPROVED = 'approved';

    const STATE_REJECTED = 'rejected';

    protected $fillable = ['invoice_id', 'student_id', 'reason', 'amount', 'state', 'approved_by'];

    protected $casts = ['amount' => 'float'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
