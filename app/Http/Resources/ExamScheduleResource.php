<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam_id' => $this->exam_id,
            'subject_id' => $this->subject_id,
            'subject_name' => $this->subject?->name,
            'course_id' => $this->course_id,
            'date' => $this->date?->toDateString(),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'max_marks' => (float) $this->max_marks,
            'pass_marks' => (float) $this->pass_marks,
        ];
    }
}
