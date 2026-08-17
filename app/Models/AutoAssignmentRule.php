<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoAssignmentRule extends Model
{
    const TYPE_LEAD = 'lead';

    const TYPE_GROUP = 'group';

    protected $fillable = ['name', 'subject_id', 'priority', 'max_students', 'rule_type', 'active'];

    protected $casts = ['priority' => 'integer', 'max_students' => 'integer', 'active' => 'boolean'];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
