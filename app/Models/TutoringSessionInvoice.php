<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoringSessionInvoice extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_SENT = 'sent';

    const STATE_PAID = 'paid';

    protected $fillable = ['reference', 'subscription_id', 'study_group_session_id', 'amount', 'state'];

    protected $casts = ['amount' => 'float'];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function studyGroupSession()
    {
        return $this->belongsTo(StudyGroupSession::class);
    }
}
