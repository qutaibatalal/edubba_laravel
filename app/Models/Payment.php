<?php

namespace App\Models;

use App\Support\Auditable;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use Auditable;

    const STATE_DRAFT = 'draft';

    const STATE_DONE = 'done';

    const STATE_CANCELLED = 'cancelled';

    const METHOD_CASH = 'cash';

    const METHOD_CARD = 'card';

    const METHOD_TRANSFER = 'transfer';

    const METHOD_WALLET = 'wallet';

    protected $fillable = [
        'reference', 'invoice_id', 'student_id', 'parent_id', 'amount', 'method',
        'gateway', 'transaction_id', 'state', 'date',
    ];

    protected $casts = ['amount' => 'float', 'date' => 'date'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }
}
