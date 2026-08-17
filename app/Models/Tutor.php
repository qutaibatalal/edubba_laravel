<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    const STATE_ACTIVE = 'active';

    const STATE_INACTIVE = 'inactive';

    protected $fillable = ['name', 'phone', 'email', 'faculty_id', 'subjects', 'hourly_rate', 'state'];

    protected $casts = ['subjects' => 'array', 'hourly_rate' => 'float'];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function availabilities()
    {
        return $this->hasMany(TutorAvailability::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function studyGroups()
    {
        return $this->hasMany(StudyGroup::class);
    }

    public function performances()
    {
        return $this->hasMany(TutorPerformance::class);
    }
}
