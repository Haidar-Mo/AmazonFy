<?php

namespace App\Http\Requests\Api\V1\Merchant;

use Illuminate\Foundation\Http\FormRequest;

class VisaArrangementStoreRequest extends FormRequest
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
            'visa_id' => 'required|exists:visas,id',
            'fields' => 'required|array',
            'fields.*.field_id' => 'required|exists:visa_required_fields,id',
            'fields.*.value' => 'required|string',

            'documents' => 'sometimes|array',
            'documents.*.document_id' => 'required_with:documents|exists:visa_required_fields,id',
            'documents.*.file' => 'required_with:documents|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
    }
}
