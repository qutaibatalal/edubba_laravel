<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'date' => $this->date?->toDateString(),
            'due_date' => $this->due_date?->toDateString(),
            'subtotal' => (float) $this->subtotal,
            'tax' => (float) $this->tax,
            'total' => (float) $this->total,
            'paid' => (float) $this->paid,
            'balance' => (float) $this->balance,
            'state' => $this->state,
            'lines' => InvoiceLineResource::collection($this->whenLoaded('lines')),
        ];
    }
}
