<?php

namespace App\Http\Requests\Api\V1\Merchant;

use Illuminate\Foundation\Http\FormRequest;

class StoreShopRequest extends FormRequest
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
            'name' => ['required', 'string', 'unique:shops,name'],
            'phone_number' => ['required', 'string', 'unique:shops,phone_number'],
            'identity_number' => ['required', 'string', 'unique:shops,identity_number'],
            'logo' => ['required', 'image'],
            'identity_front_face' => ['required', 'image'],
            'identity_back_face' => ['required', 'image'],
            'shop_type_id' => ['required', 'exists:shop_types,id'],
            'address' => ['required', 'string'],
            'representative_code' => ['required', 'string']
        ];
    }
}
