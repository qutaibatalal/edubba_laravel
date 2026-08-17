<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_id' => $this->student_id,
            'parent_id' => $this->parent_id,
            'balance' => (float) $this->balance,
            'transactions' => WalletTransactionResource::collection($this->whenLoaded('transactions')),
        ];
    }
}
