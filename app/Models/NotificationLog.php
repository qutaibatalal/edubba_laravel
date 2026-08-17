<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use HasFactory;

    const STATE_PENDING = 'pending';

    const STATE_SENT = 'sent';

    const STATE_FAILED = 'failed';

    protected $fillable = ['channel', 'recipient', 'body', 'state', 'student_id', 'api_user_id', 'error', 'sent_at', 'read_at'];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function apiUser()
    {
        return $this->belongsTo(ApiUser::class);
    }
}
