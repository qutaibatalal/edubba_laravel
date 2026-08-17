<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['student_id', 'parent_id', 'balance'];

    protected $casts = ['balance' => 'float'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function parent()
    {
        return $this->belongsTo(ParentModel::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
}
