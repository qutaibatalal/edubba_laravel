<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarksheetLineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'subject_id' => $this->subject_id,
            'subject_name' => $this->subject?->name,
            'course_id' => $this->course_id,
            'max_marks' => (float) $this->max_marks,
            'marks' => (float) $this->marks,
            'pass_marks' => (float) $this->pass_marks,
            'percentage' => (float) $this->percentage,
            'grade' => $this->grade,
            'passed' => (bool) $this->passed,
        ];
    }
}
