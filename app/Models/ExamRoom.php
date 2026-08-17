<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamRoom extends Model
{
    protected $fillable = ['name', 'code', 'location', 'capacity', 'active'];

    protected $casts = ['capacity' => 'integer', 'active' => 'boolean'];

    public function allocations()
    {
        return $this->hasMany(ExamRoomAllocation::class);
    }

    public function scopeActive($q)
    {
        return $q->where('active', true);
    }
}
