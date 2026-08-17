<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    protected $fillable = ['name', 'channel', 'body', 'active'];

    protected $casts = ['active' => 'boolean'];

    public function messages()
    {
        return $this->hasMany(WhatsappMessage::class);
    }
}
