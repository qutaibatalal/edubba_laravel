<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    protected $fillable = ['name', 'address', 'warden_name', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function rooms()
    {
        return $this->hasMany(HostelRoom::class);
    }
}
