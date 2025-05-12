<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ShopCreateRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'shop_type_id' => 'required|exists:shop_types,id',
            'name' => 'required|string',
            'phone_number' => 'required|string|unique:shops,phone_number',
            'identity_number' => 'required|string|unique:shops,identity_number',
            'logo' => 'required|image',
            'identity_front_face' => 'required|image',
            'identity_back_face' => 'required|image',
            'address' => 'required|string',
        ];
    }
}
