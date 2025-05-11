<?php

namespace App\Http\Requests\Api\V1\Merchant;

use Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->shop->user_id == Auth::user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['string',Rule::unique('shops','name')->ignore($this->shop->id)],
            'phone_number' => ['string',Rule::unique('shops','phone_number')->ignore($this->shop->id)],
            'identity_number' => ['string',Rule::unique('shops','identity_number')->ignore($this->shop->id)],
            'logo' => ['image'],
            'identity_front_face' => ['image'],
            'identity_back_face' => ['image'],
            'shop_type_id' => ['exists:shop_types,id'],
            'address' => ['string'],
        ];
    }
}
