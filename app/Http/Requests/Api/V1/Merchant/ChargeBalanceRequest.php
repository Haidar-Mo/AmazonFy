<?php

namespace App\Http\Requests\Api\V1\Merchant;

use Illuminate\Foundation\Http\FormRequest;

class ChargeBalanceRequest extends FormRequest
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
            'amount' => ['required', 'numeric'],
            'target' => ['required', 'string', 'exists:wallet_addresses,target'],
            'charge_network' => ['required', 'string'],
            'coin_type' => ['required', 'string'],
            'image' => ['required', 'image'],
        ];
    }

    // public function messages() {
    //     return [
    //         'amount.decimal' => 'amount fiels must be decimal number'
    //     ];
    // }
}
