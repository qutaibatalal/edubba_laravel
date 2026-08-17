<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use SoftDeletes;

    const STATE_DRAFT = 'draft';

    const STATE_ACTIVE = 'active';

    const STATE_PAUSED = 'paused';

    const STATE_EXPIRED = 'expired';

    const STATE_CANCELLED = 'cancelled';

    protected $fillable = [
        'reference', 'student_id', 'parent_id', 'tutor_id', 'study_group_id',
        'package_id', 'product_id', 'start_date', 'end_date', 'frequency',
        'sessions_count', 'sessions_used', 'amount', 'paid_amount', 'state',
        'next_renewal_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_renewal_date' => 'date',
        'sessions_count' => 'integer',
        'sessions_used' => 'integer',
        'amount' => 'float',
        'paid_amount' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function tutor()
    {
        return $this->belongsTo(Tutor::class);
    }

    public function studyGroup()
    {
        return $this->belongsTo(StudyGroup::class);
    }

    public function package()
    {
        return $this->belongsTo(TutoringPackage::class);
    }

    public function product()
    {
        return $this->belongsTo(TutoringProduct::class);
    }

    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function renewals()
    {
        return $this->hasMany(SubscriptionRenewal::class);
    }
}
