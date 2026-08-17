<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMessage extends Model
{
    const STATE_PENDING = 'pending';

    const STATE_SENT = 'sent';

    const STATE_FAILED = 'failed';

    protected $fillable = ['template_id', 'recipient', 'body', 'state', 'error'];

    public function template()
    {
        return $this->belongsTo(WhatsappTemplate::class);
    }
}
