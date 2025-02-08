<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Return true if all authenticated users are allowed to make this request.
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, string|array>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female,rather_not_to_say',
            'address' => 'nullable|string|max:255',
            'phonenumber' => 'nullable|string|max:20',

        ];
    }

    /**
     * Get the custom messages for validation errors.
     *
     * @return array<string, string>
     */

}
