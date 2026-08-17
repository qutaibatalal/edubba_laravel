<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'application_no' => $this->application_no,
            'name' => $this->name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date?->toDateString(),
            'national_id' => $this->national_id,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'previous_school' => $this->previous_school,
            'fees_amount' => (float) $this->fees_amount,
            'state' => $this->state,
            'student_id' => $this->student_id,
            'academic_year_id' => $this->academic_year_id,
            'batch_id' => $this->batch_id,
            'program_id' => $this->program_id,
            'register_id' => $this->register_id,
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
