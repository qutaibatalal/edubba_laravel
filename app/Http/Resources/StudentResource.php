<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'student_code' => $this->student_code,
            'name' => $this->name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date?->toDateString(),
            'birth_place' => $this->birth_place,
            'national_id' => $this->national_id,
            'residence' => $this->residence,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'province' => $this->province,
            'photo' => $this->photo,
            'roll_no' => $this->roll_no,
            'state' => $this->state,
            'admission_date' => $this->admission_date?->toDateString(),
            'batch_id' => $this->batch_id,
            'program_id' => $this->program_id,
            'academic_year_id' => $this->academic_year_id,
            'parent_id' => $this->parent_id,
            'batch' => $this->whenLoaded('batch', fn () => ['id' => $this->batch->id, 'name' => $this->batch->name]),
            'program' => $this->whenLoaded('program', fn () => ['id' => $this->program->id, 'name' => $this->program->name]),
            'academic_year' => $this->whenLoaded('academicYear', fn () => ['id' => $this->academicYear->id, 'name' => $this->academicYear->name]),
        ];
    }
}
