<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreCategory extends Model
{
    protected $fillable = ['name', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function products()
    {
        return $this->hasMany(StoreProduct::class);
    }
}
