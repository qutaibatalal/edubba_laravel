<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryBook extends Model
{
    protected $fillable = ['title', 'author', 'isbn', 'category', 'total_qty', 'available_qty', 'active'];

    protected $casts = ['total_qty' => 'integer', 'available_qty' => 'integer', 'active' => 'boolean'];

    public function issues()
    {
        return $this->hasMany(LibraryIssue::class);
    }
}
