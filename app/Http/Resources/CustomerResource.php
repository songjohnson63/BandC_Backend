<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'address' => $this->address,
            'phonenumber' => $this->phonenumber,
 
        ];
    }
}
