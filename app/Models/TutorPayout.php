<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorPayout extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_PAID = 'paid';

    const STATE_CANCELLED = 'cancelled';

    protected $fillable = ['reference', 'tutor_id', 'period_start', 'period_end', 'total_hours', 'amount', 'state'];

    protected $casts = ['period_start' => 'date', 'period_end' => 'date', 'total_hours' => 'float', 'amount' => 'float'];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function lines()
    {
        return $this->hasMany(TutorPayoutLine::class);
    }
}
