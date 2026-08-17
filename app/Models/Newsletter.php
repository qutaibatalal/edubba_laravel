<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_SENT = 'sent';

    protected $fillable = ['title', 'body', 'send_date', 'state'];

    protected $casts = ['send_date' => 'date'];
}
