<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoringContract extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_ACTIVE = 'active';

    const STATE_ENDED = 'ended';

    protected $fillable = [
        'reference', 'tutor_id', 'start_date', 'end_date', 'hourly_rate',
        'commission_rate', 'state', 'terms',
    ];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'hourly_rate' => 'float', 'commission_rate' => 'float'];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }
}
