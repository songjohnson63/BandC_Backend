<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'product_type_id' => 'nullable|exists:product_types,id',
            'description' => 'nullable|string|max:1000', // Corrected text -> string
            'volume' => 'required|string|max:255',
            'key_ingredient' => 'nullable|string|max:1000', // Corrected text -> string
            'best_seller' => 'nullable|boolean',
            'discount' => 'nullable|numeric|min:0', // Changed to numeric
            'price' => 'nullable|numeric|min:0', // Changed to numeric
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the uploaded image
        ];


        
    }
}
