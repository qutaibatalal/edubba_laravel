<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClassSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date?->toDateString(),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'state' => $this->state,
            'topic' => $this->topic,
            'notes' => $this->notes,
            'batch_id' => $this->batch_id,
            'batch_name' => $this->batch?->name,
            'course_id' => $this->course_id,
            'course_name' => $this->course?->name,
            'subject_id' => $this->subject_id,
            'subject_name' => $this->subject?->name,
            'faculty_id' => $this->faculty_id,
            'faculty_name' => $this->faculty?->full_name,
            'classroom_id' => $this->classroom_id,
            'classroom_name' => $this->classroom?->name,
        ];
    }
}
