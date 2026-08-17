<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorPayoutLine extends Model
{
    protected $fillable = ['tutor_payout_id', 'study_group_session_id', 'hours', 'rate', 'amount'];

    protected $casts = ['hours' => 'float', 'rate' => 'float', 'amount' => 'float'];

    public function tutorPayout()
    {
        return $this->belongsTo(TutorPayout::class);
    }

    public function studyGroupSession()
    {
        return $this->belongsTo(StudyGroupSession::class);
    }
}
