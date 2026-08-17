<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudyGroupAttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'study_group_session_id' => $this->study_group_session_id,
            'student_id' => $this->student_id,
            'student_name' => $this->student?->full_name,
            'status' => $this->status,
        ];
    }
}
