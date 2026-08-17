<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use Auditable, SoftDeletes;

    const STATE_DRAFT = 'draft';

    const STATE_OPEN = 'open';

    const STATE_PAID = 'paid';

    const STATE_CANCEL = 'cancel';

    protected $fillable = [
        'number', 'student_id', 'parent_id', 'academic_year_id', 'date', 'due_date',
        'subtotal', 'tax', 'total', 'paid', 'balance', 'state', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'float',
        'tax' => 'float',
        'total' => 'float',
        'paid' => 'float',
        'balance' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function lines()
    {
        return $this->hasMany(InvoiceLine::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }
}
