<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorPayment extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_PAID = 'paid';

    protected $fillable = ['trainer_id', 'period_start', 'period_end', 'hours', 'amount', 'state'];

    protected $casts = ['period_start' => 'date', 'period_end' => 'date', 'hours' => 'float', 'amount' => 'float'];

    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }
}
