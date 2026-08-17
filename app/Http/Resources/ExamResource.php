<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'exam_type_id' => $this->exam_type_id,
            'exam_type' => $this->whenLoaded('examType', fn () => $this->examType?->name),
            'date_start' => $this->date_start?->toDateString(),
            'date_end' => $this->date_end?->toDateString(),
            'state' => $this->state,
            'schedules' => ExamScheduleResource::collection($this->whenLoaded('schedules')),
        ];
    }
}
