<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admission extends Model
{
    use SoftDeletes;

    const STATE_DRAFT = 'draft';

    const STATE_SUBMIT = 'submit';

    const STATE_APPROVE = 'approve';

    const STATE_REJECT = 'reject';

    const STATE_ADMITTED = 'admitted';

    protected $fillable = [
        'application_no', 'register_id', 'academic_year_id', 'batch_id', 'program_id',
        'name', 'middle_name', 'last_name', 'birth_date', 'gender', 'national_id',
        'phone', 'email', 'address', 'previous_school', 'fees_amount', 'state',
        'student_id', 'notes', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'fees_amount' => 'float',
    ];

    public function register()
    {
        return $this->belongsTo(AdmissionRegister::class, 'register_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->name} {$this->middle_name} {$this->last_name}");
    }
}
