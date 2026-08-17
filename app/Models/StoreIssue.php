<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreIssue extends Model
{
    protected $fillable = ['product_id', 'student_id', 'qty', 'date', 'note'];

    protected $casts = ['qty' => 'integer', 'date' => 'date'];

    public function product()
    {
        return $this->belongsTo(StoreProduct::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
