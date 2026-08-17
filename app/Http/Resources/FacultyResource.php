<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FacultyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'faculty_code' => $this->faculty_code,
            'name' => $this->name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date?->toDateString(),
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'qualification' => $this->qualification,
            'specialization' => $this->specialization,
            'join_date' => $this->join_date?->toDateString(),
            'department_id' => $this->department_id,
            'department' => $this->whenLoaded('department', fn () => ['id' => $this->department->id, 'name' => $this->department->name]),
            'state' => $this->state,
            'photo' => $this->photo,
        ];
    }
}
