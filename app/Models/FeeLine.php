<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeLine extends Model
{
    protected $fillable = ['fee_structure_id', 'name', 'amount', 'type', 'sequence'];

    protected $casts = ['amount' => 'float', 'sequence' => 'integer'];

    const TYPE_ONE_TIME = 'one_time';

    const TYPE_RECURRING = 'recurring';

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }
}
