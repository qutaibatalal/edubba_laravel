<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryReturn extends Model
{
    protected $fillable = ['library_issue_id', 'return_date', 'fine_applied', 'note'];

    protected $casts = ['return_date' => 'date', 'fine_applied' => 'float'];

    public function libraryIssue()
    {
        return $this->belongsTo(LibraryIssue::class);
    }
}
