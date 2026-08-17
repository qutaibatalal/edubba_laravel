<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'wallet_id' => $this->wallet_id,
            'type' => $this->type,
            'amount' => (float) $this->amount,
            'reference' => $this->reference,
            'description' => $this->description,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
