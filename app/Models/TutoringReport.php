<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutoringReport extends Model
{
    const STATE_DRAFT = 'draft';

    const STATE_GENERATED = 'generated';

    protected $fillable = ['name', 'report_type', 'data', 'state'];

    protected $casts = ['data' => 'array'];
}
