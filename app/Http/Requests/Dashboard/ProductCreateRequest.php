<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
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
            'title' => 'required|string',
            'details' => 'required|string',
            'type' => 'required|string',
            'wholesale_price' => 'required|decimal:0,99999999',
            'selling_price' => 'required|decimal:0,99999999',
            'is_available' => 'boolean',
        ];
    }
}
