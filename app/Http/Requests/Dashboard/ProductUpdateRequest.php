<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
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
            'type_id' => 'sometimes|exists:product_types,id',
            'wholesale_price' => 'sometimes|numeric',
            'selling_price' => 'sometimes|numeric',
            'is_available' => 'sometimes|boolean',
            'image' => 'sometimes|image',

            // Validate translations
            'title_ar' => 'sometimes|string|max:255',
            'details_ar' => 'sometimes|string',
            'title_en' => 'sometimes|string|max:255',
            'details_en' => 'sometimes|string',
        ];
    }
}
