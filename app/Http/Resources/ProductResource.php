<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'brand' => $this->brand,
            'product_type' => [
                'id' => $this->productType->id,
                'type_name' => $this->productType->type_name,
            ],
            'description' => $this->description,
            'volume' => $this->volume,
            'key_ingredient' => $this->key_ingredient,
            'ori_price' => $this->ori_price,
            'price' => $this->price,
            'img' => $this->img,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
