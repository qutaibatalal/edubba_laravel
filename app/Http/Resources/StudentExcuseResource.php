<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentExcuseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'date' => $this->date?->toDateString(),
            'reason' => $this->reason,
            'document' => $this->document,
            'state' => $this->state,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
