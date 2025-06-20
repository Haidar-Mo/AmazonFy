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
            'title' => 'required|string|max:255',
            'details' => 'required|string',
            'type_id' => 'required|exists:product_types,id',
            'is_available' => 'sometimes|boolean',
            'selling_price' => 'required|numeric',
            'wholesale_price' => 'required|numeric',
            'image' => 'nullable|image',
        ];
    }
}
