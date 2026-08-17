<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->sheet?->date?->toDateString(),
            'course_id' => $this->sheet?->course_id,
            'session_id' => $this->sheet?->session_id,
            'status' => $this->status,
            'note' => $this->note,
        ];
    }
}
