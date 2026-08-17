<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'national_id' => $this->national_id,
            'address' => $this->address,
            'occupation' => $this->occupation,
            'relation' => $this->relation,
            'photo' => $this->photo,
            'children' => StudentResource::collection($this->whenLoaded('children')),
        ];
    }
}
