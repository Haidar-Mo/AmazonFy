<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisaRequiredFieldResource extends JsonResource
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
            'key' => $this->key,
            'type' => $this->type,
            'is_required' => (bool) $this->is_required,
            'label' => $this->label
                ?? optional($this->translations->firstWhere('locale', app()->getLocale()))->label
                ?? optional($this->translations->first())->label,

/*             'created_at' => optional($this->created_at)?->toISOString(),
            'updated_at' => optional($this->updated_at)?->toISOString(),
 */        ];
    }
}
