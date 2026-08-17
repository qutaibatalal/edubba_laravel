<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'credit_hours' => $this->credit_hours,
            'syllabus' => $this->syllabus,
            'subject_id' => $this->subject_id,
            'subject' => $this->whenLoaded('subject', fn () => ['id' => $this->subject->id, 'name' => $this->subject->name]),
            'faculty' => $this->whenLoaded('faculty', fn () => ['id' => $this->faculty->id, 'name' => $this->faculty->full_name]),
            'batch' => $this->whenLoaded('batch', fn () => ['id' => $this->batch->id, 'name' => $this->batch->name]),
            'program' => $this->whenLoaded('program', fn () => ['id' => $this->program->id, 'name' => $this->program->name]),
        ];
    }
}
