<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketReservationResource extends JsonResource
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
            'user_id' => $this->user_id,
            'air_line_id' => $this->air_line_id,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'ticket_name' => $this->ticket_name,
            'user_name' => $this->user_name,

            'fields' => collect($this->fields)->map(function ($field) {
                return [
                    'id' => $field['id'] ?? $field->id,
                    'ticket_reservation_id' => $field['ticket_reservation_id'] ?? $field->ticket_reservation_id,
                    'air_line_required_field_id' => $field['air_line_required_field_id'] ?? $field->air_line_required_field_id,
                    'value' => (($field['is_file'] ?? $field->is_file) == 1)
                        ? asset($field['value'] ?? $field->value)
                        : ($field['value'] ?? $field->value),
                    'created_at' => $field['created_at'] ?? $field->created_at,
                    'updated_at' => $field['updated_at'] ?? $field->updated_at,
                    'key' => $field['key'] ?? $field->key,
                    'is_file' => $field['is_file'] ?? $field->is_file,
                ];
            })->values(),

            'air_line' => $this->airLine()
        ];
    }
}
