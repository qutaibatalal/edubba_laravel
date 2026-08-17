<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    const STATE_PLANNED = 'planned';

    const STATE_ONGOING = 'ongoing';

    const STATE_DONE = 'done';

    const STATE_CANCELLED = 'cancelled';

    protected $fillable = ['name', 'description', 'date', 'venue', 'capacity', 'state'];

    protected $casts = ['date' => 'date', 'capacity' => 'integer'];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
