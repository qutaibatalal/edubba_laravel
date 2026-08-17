<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use Auditable, SoftDeletes;

    const STATE_DRAFT = 'draft';

    const STATE_ADMITTED = 'admitted';

    const STATE_GRADUATED = 'graduated';

    const STATE_ALUMNI = 'alumni';

    protected $fillable = [
        'student_code', 'name', 'middle_name', 'last_name', 'gender', 'birth_date',
        'birth_place', 'national_id', 'residence', 'marital_status', 'blood_group',
        'phone', 'mobile', 'email', 'address', 'city', 'province', 'country', 'zip',
        'photo', 'partner_id', 'batch_id', 'program_id', 'academic_year_id', 'parent_id',
        'department_id', 'state', 'admission_date', 'roll_no', 'medical_note', 'note',
        'active', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'admission_date' => 'date',
        'active' => 'boolean',
    ];

    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function parents()
    {
        return $this->belongsToMany(ParentModel::class, 'student_parent', 'student_id', 'parent_id')
            ->withPivot(['relation', 'is_main', 'guardian', 'emergency_contact']);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'student_course')
            ->withPivot(['batch_id', 'academic_year_id', 'state', 'total_fees'])
            ->withTimestamps();
    }

    public function attendances()
    {
        return $this->hasMany(AttendanceLine::class);
    }

    public function marksheets()
    {
        return $this->hasMany(Marksheet::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function excuses()
    {
        return $this->hasMany(StudentExcuse::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getFullNameAttribute()
    {
        return trim("{$this->name} {$this->middle_name} {$this->last_name}");
    }

    public function scopeAdmitted($q)
    {
        return $q->where('state', self::STATE_ADMITTED);
    }

    public function examRoomAllocations()
    {
        return $this->hasMany(ExamRoomAllocation::class);
    }

    public function assignmentSubmissions()
    {
        return $this->hasMany(AssignmentSubmission::class);
    }

    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    public function libraryIssues()
    {
        return $this->hasMany(LibraryIssue::class);
    }
}
