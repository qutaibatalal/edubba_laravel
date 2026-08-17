<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceSheetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'batch_id' => $this->batch_id,
            'course_id' => $this->course_id,
            'faculty_id' => $this->faculty_id,
            'date' => $this->date?->toDateString(),
            'state' => $this->state,
            'lines' => $this->whenLoaded('lines', fn () => $this->lines->map(fn ($l) => [
                'id' => $l->id,
                'student_id' => $l->student_id,
                'student_name' => $l->student?->full_name,
                'status' => $l->status,
                'note' => $l->note,
            ])->values()),
        ];
    }
}
