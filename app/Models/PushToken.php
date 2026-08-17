<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PushToken extends Model
{
    protected $fillable = ['api_user_id', 'device_id', 'token', 'provider', 'device_type', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function apiUser()
    {
        return $this->belongsTo(ApiUser::class);
    }
}
