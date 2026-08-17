<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryIssue extends Model
{
    const STATE_ISSUED = 'issued';

    const STATE_RETURNED = 'returned';

    const STATE_OVERDUE = 'overdue';

    protected $fillable = ['book_id', 'student_id', 'issue_date', 'due_date', 'return_date', 'fine', 'state'];

    protected $casts = ['issue_date' => 'date', 'due_date' => 'date', 'return_date' => 'date', 'fine' => 'float'];

    public function book()
    {
        return $this->belongsTo(LibraryBook::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fines()
    {
        return $this->hasMany(LibraryFine::class);
    }

    public function returns()
    {
        return $this->hasMany(LibraryReturn::class);
    }
}
