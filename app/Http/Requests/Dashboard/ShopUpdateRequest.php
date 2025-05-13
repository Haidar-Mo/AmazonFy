<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ShopUpdateRequest extends FormRequest
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
            'shop_type_id' => 'sometimes|exists:shop_types,id',
            'name' => 'sometimes|string',
            'phone_number' => 'sometimes|string|unique:shops,phone_number',
            'identity_number' => 'sometimes|string|unique:shops,identity_number',
            'logo' => 'sometimes|image',
            'identity_front_face' => 'sometimes|image',
            'identity_back_face' => 'sometimes|image',
            'address' => 'sometimes|string',
        ];
    }
}
