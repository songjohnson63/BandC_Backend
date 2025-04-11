<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'phonenumber' => $this->phonenumber,
            'address' => $this->address,
            'city_province' => $this->cityProvince ? [
                'id' => $this->cityProvince->id,
                'name' => $this->cityProvince->name,
            ] : null, // ✅ Prevents errors if cityProvince is null
            'password' => $this->password,
 
        ];
    }
}
