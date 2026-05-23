<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AirLineResource extends JsonResource
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
            'iata_code' => $this->iata_code,
            'is_active' => $this->is_active,
            'fields' => $this->requiredFields ?
                collect($this->requiredFields)
                    ->where('is_file', false)
                    ->all() : null,
            'documents' => $this->requiredFields ?
                collect($this->requiredFields)
                    ->where('is_file', true)
                    ->all()
                : null,

            'created_at' => $this->created_at,
        ];
    }
}
