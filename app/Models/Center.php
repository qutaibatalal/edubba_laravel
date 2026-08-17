<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Center extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function studyGroups()
    {
        return $this->hasMany(StudyGroup::class);
    }
}
