<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'source_id' => $this->source_id,
            'source' => $this->whenLoaded('source', fn () => $this->source?->name),
            'stage_id' => $this->stage_id,
            'stage' => $this->whenLoaded('stage', fn () => $this->stage?->name),
            'expected_value' => (float) $this->expected_value,
            'state' => $this->state,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
