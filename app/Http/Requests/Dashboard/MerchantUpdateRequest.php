<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class MerchantUpdateRequest extends FormRequest
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
        $userId = $this->route('id');
        return [
            'name' => 'sometimes|string',
            'email' => "sometimes|string|unique:users,email,{$userId}",
            'phone_number' => "sometimes|string|unique:users,phone_number,{$userId}",
            'password' => 'sometimes|confirmed|min:6'
        ];
    }
}
