<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreRequest extends Model
{
    const STATE_PENDING = 'pending';

    const STATE_APPROVED = 'approved';

    const STATE_REJECTED = 'rejected';

    protected $fillable = ['product_id', 'student_id', 'qty', 'state'];

    protected $casts = ['qty' => 'integer'];

    public function product()
    {
        return $this->belongsTo(StoreProduct::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
