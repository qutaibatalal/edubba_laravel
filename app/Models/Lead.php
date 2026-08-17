<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    const STATE_NEW = 'new';

    const STATE_WON = 'won';

    const STATE_LOST = 'lost';

    protected $fillable = [
        'name', 'phone', 'email', 'source_id', 'stage_id', 'assigned_to',
        'expected_value', 'state', 'notes',
    ];

    protected $casts = ['expected_value' => 'float'];

    public function source()
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    public function stage()
    {
        return $this->belongsTo(LeadStage::class, 'stage_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
