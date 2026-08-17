<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commission extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_CALCULATED = 'calculated';

    const STATE_PAID = 'paid';

    protected $fillable = ['reference', 'tutor_id', 'agent_id', 'base_amount', 'rate', 'amount', 'state'];

    protected $casts = ['base_amount' => 'float', 'rate' => 'float', 'amount' => 'float'];

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function lines()
    {
        return $this->hasMany(CommissionLine::class);
    }
}
