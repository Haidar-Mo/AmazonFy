<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisaRequest extends FormRequest
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
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'duration' => 'required|numeric',
            'price' => 'required|numeric|min:0',

            'required_fields' => 'array',
            'required_fields.*.key' => 'required|string|max:255',
            'required_fields.*.label_en' => 'required|string|max:255',
            'required_fields.*.label_ar' => 'required|string|max:255',
            'required_fields.*.type' => 'required|string|in:text,date,number,email,file,image,pdf',
            'required_fields.*.is_file' => 'boolean',
            'required_fields.*.is_required' => 'boolean',
        ];
    }
}
