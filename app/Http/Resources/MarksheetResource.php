<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarksheetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam_id' => $this->exam_id,
            'exam_name' => $this->exam?->name,
            'student_id' => $this->student_id,
            'batch_id' => $this->batch_id,
            'total_marks' => (float) $this->total_marks,
            'obtained_marks' => (float) $this->obtained_marks,
            'percentage' => (float) $this->percentage,
            'grade' => $this->grade,
            'result' => $this->result,
            'rank' => $this->rank,
            'state' => $this->state,
            'lines' => MarksheetLineResource::collection($this->whenLoaded('lines')),
        ];
    }
}
