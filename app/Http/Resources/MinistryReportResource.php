<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MinistryReportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'academic_year_id' => $this->academic_year_id,
            'academic_year' => $this->whenLoaded('academicYear', fn () => $this->academicYear?->name),
            'term_id' => $this->term_id,
            'report_type' => $this->report_type,
            'state' => $this->state,
            'data' => $this->data,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
