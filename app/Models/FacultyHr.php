<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultyHr extends Model
{
    protected $table = 'faculty_hr';

    protected $fillable = [
        'faculty_id', 'employee_type', 'contract_start', 'contract_end', 'salary',
        'bank_name', 'bank_account', 'tin', 'documents',
    ];

    protected $casts = [
        'contract_start' => 'date',
        'contract_end' => 'date',
        'salary' => 'float',
        'documents' => 'array',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
}
