<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'student_id' => $this->student_id,
            'tutor_id' => $this->tutor_id,
            'study_group_id' => $this->study_group_id,
            'package_id' => $this->package_id,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'frequency' => $this->frequency,
            'sessions_count' => $this->sessions_count,
            'sessions_used' => $this->sessions_used,
            'amount' => (float) $this->amount,
            'paid_amount' => (float) $this->paid_amount,
            'balance' => (float) max(0, $this->amount - $this->paid_amount),
            'state' => $this->state,
            'next_renewal_date' => $this->next_renewal_date?->toDateString(),
            'study_group' => $this->whenLoaded('studyGroup', fn () => ['id' => $this->studyGroup->id, 'name' => $this->studyGroup->name]),
            'tutor' => $this->whenLoaded('tutor', fn () => ['id' => $this->tutor->id, 'name' => $this->tutor->name]),
        ];
    }
}
