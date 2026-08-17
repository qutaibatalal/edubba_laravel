<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyGroupSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'study_group_id' => $this->study_group_id,
            'study_group_name' => $this->studyGroup?->name,
            'tutor_id' => $this->tutor_id,
            'date' => $this->date?->toDateString(),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'state' => $this->state,
            'notes' => $this->notes,
            'attendances' => StudyGroupAttendanceResource::collection($this->whenLoaded('attendances')),
        ];
    }
}
