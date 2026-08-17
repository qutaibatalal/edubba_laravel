<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryFine extends Model
{
    const STATE_PENDING = 'pending';

    const STATE_PAID = 'paid';

    protected $fillable = ['library_issue_id', 'amount', 'reason', 'state'];

    protected $casts = ['amount' => 'float'];

    public function libraryIssue()
    {
        return $this->belongsTo(LibraryIssue::class);
    }
}
