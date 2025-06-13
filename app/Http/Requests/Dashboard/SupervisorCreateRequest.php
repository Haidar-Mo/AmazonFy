<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SupervisorCreateRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required_if:phone_number,null|sometimes|unique:users,email',
            'phone_number' => 'required_if:email,null|sometimes|unique:users,phone_number',
            'password' => 'required|min:8',
            'permissions' => 'required|array',
            'permissions*' => 'exists:permissions,id'
        ];
    }
}
