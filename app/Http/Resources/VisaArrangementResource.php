<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisaArrangementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'visa_id' => $this->visa_id,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'cover_letter' => $this->cover_letter,
            'pdf_path' => $this->pdf_path,
            "visa_name" => $this->visa_name,
            "user_name" => $this->user_name,
            "fields" => $this->fields ?
                collect($this->fields)
                    ->where('visaRequiredField.type', '!=', 'file')
                    ->mapWithKeys(function ($fv) {
                        return [
                            data_get($fv, 'key') => data_get($fv, 'value'),
                        ];
                    })->all()
                : [],
            "documents" => $this->fields ?
                collect($this->fields)
                    ->where('visaRequiredField.type', '=', 'file')
                    ->mapWithKeys(function ($dp) {
                        return [
                            data_get($dp, 'key') => asset(data_get($dp, 'value')),
                        ];
                    })->all()
                : [],

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
