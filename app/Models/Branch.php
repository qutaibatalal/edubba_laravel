<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['center_id', 'name', 'address', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function center()
    {
        return $this->belongsTo(Center::class);
    }
}
