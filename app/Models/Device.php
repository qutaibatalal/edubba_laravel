<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['device_token', 'platform', 'model', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function pushTokens()
    {
        return $this->hasMany(PushToken::class);
    }
}
