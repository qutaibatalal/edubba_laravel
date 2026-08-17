<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingCertificate extends Model
{
    protected $fillable = ['certificate_no', 'training_enrollment_id', 'issued_date', 'document'];

    protected $casts = ['issued_date' => 'date'];

    public function enrollment()
    {
        return $this->belongsTo(TrainingEnrollment::class, 'training_enrollment_id');
    }
}
