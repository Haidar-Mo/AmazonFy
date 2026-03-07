<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class AirLineUpdateRequest extends FormRequest
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
            'name_en' => ['sometimes', 'string', 'max:255'],
            'name_ar' => ['sometimes', 'string', 'max:255'],
            'iata_code' => ['sometimes', 'string', 'max:2'],
            'is_active' => ['sometimes', 'boolean'],

            'required_fields' => ['nullable', 'array'],
            'required_fields.*.id' => 'sometimes|exists:visa_required_fields,id',
            'required_fields.*.key' => 'required|string|max:255',
            'required_fields.*.label_en' => ['required_with:required_fields', 'string', 'max:255'],
            'required_fields.*.label_ar' => ['required_with:required_fields', 'string', 'max:255'],
            'required_fields.*.type' => 'required|string|in:text,date,number,email,file,image,pdf',
            'required_fields.*.is_file' => ['required_with:required_fields', 'boolean'],
            'required_fields.*.is_required' => 'boolean',
        ];
    }
}
