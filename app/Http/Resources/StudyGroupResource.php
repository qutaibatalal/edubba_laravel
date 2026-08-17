<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyGroupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subject_id' => $this->subject_id,
            'subject' => $this->whenLoaded('subject', fn () => ['id' => $this->subject->id, 'name' => $this->subject->name]),
            'tutor_id' => $this->tutor_id,
            'center_id' => $this->center_id,
            'max_students' => $this->max_students,
            'level' => $this->level,
            'state' => $this->state,
            'students_count' => $this->whenCounted('students'),
            'students' => StudentResource::collection($this->whenLoaded('students')),
        ];
    }
}
