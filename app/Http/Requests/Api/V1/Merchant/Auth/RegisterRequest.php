<?php

namespace App\Http\Requests\Api\V1\Merchant\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'phone_number' => [
                'required_without:email',  // Required when email is not present
                'prohibits:email',          // Prevent email from being submitted if phone_number exists
                'unique:users,phone_number'
            ],
            'email' => [
                'required_without:phone_number',  // Required when phone_number is not present
                'prohibits:phone_number',         // Prevent phone_number from being submitted if email exists
                'email',
                'unique:users,email'
            ],
            'password' => ['required', 'confirmed'],
            'deviceToken' => ['nullable'],
        ];
    }
}
