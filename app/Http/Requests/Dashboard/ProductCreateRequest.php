<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            'type_id' => 'required|exists:product_types,id',
            'is_available' => 'sometimes|boolean',
            'selling_price' => 'required|numeric',
            'wholesale_price' => 'required|numeric',
            'image' => 'required|image',

            // Validate translations
            'title_ar' => 'required_without:title_en|required_with:details_ar|string|max:255',
            'details_ar' => 'required_without:details_en|required_with:title_ar|string',
            'title_en' => 'required_without:title_ar|required_with:details_en|string|max:255',
            'details_en' => 'required_without:details_ar|required_with:title_en|string',
        ];
    }
}
