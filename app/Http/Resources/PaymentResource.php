<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'invoice_id' => $this->invoice_id,
            'amount' => (float) $this->amount,
            'method' => $this->method,
            'gateway' => $this->gateway,
            'transaction_id' => $this->transaction_id,
            'state' => $this->state,
            'date' => $this->date?->toDateString(),
        ];
    }
}
