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
            'name' => ['required', 'string'],
            'phone_number' => ['required', 'string'],
            'identity_number' => ['required', 'string'],
            'logo' => ['required', 'image'],
            'identity_front_face' => ['required', 'image'],
            'identity_back_face' => ['required', 'image'],
            'type' => ['required'],
            'address' => ['required', 'string'],
        ];
    }
}
