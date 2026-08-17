<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintCategory extends Model
{
    protected $fillable = ['name', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'category_id');
    }
}
