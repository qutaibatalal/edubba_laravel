<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_GENERATED = 'generated';

    protected $fillable = ['student_id', 'type', 'document', 'issue_date', 'state'];

    protected $casts = ['issue_date' => 'date'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
