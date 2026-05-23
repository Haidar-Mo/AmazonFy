<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisaResource extends JsonResource
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
            'name' => $this->name,
            'price' => $this->price,
            'destination' => $this->destination,
            'description' => $this->description,
            'fields' => VisaRequiredFieldResource::collection(
                $this->whenLoaded('requiredFields', function ($field) {
                    return $field->where('type', '!=', 'file');
                })
            ),
            'documents' => VisaRequiredFieldResource::collection(
                $this->whenLoaded('requiredFields', function ($field) {
                    return $field->where('type', 'file');
                })
            ),
            'created_at' => $this->created_at,
        ];
    }
}
