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
            'best_seller' => $this->best_seller,
            'price' => $this->price,
            'discount' => $this->discount,
            'price_after_discount'  => $this->price_after_discount,
            'favorited_by_current_user' => $this->favorited_by_current_user,
            // 'img' => $this->img,
            'img' => $this->img ? asset('storage/' . $this->img) : null,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
