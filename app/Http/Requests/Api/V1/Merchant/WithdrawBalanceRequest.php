<?php

namespace App\Http\Requests\Api\V1\Merchant;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawBalanceRequest extends FormRequest
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
        $wallet = $this->user()->wallet;
        return [
            'amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) use ($wallet) {
                    if ($value >= $wallet->available_balance) {
                        $fail('You do not have enough balance.');
                    }
                },
            ],
            'target' => ['required', 'string', 'exists:wallet_addresses,target'],
            'charge_network' => ['required', 'string'],
            'coin_type' => ['required', 'string'],
        ];
    }
}
