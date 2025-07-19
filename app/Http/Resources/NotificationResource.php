<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray($request)
    {
        $locale = app()->getLocale();

        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => data_get($this->data, "{$locale}.title"),
            'body' => data_get($this->data, "{$locale}.body"),
            'notification_type' => data_get($this->data, "{$locale}.notification_type"),
            'model_id' => data_get($this->data, "{$locale}.model_id"),
            'read_at' => $this->read_at,
            'created_at' => $this->created_at,
        ];
    }
}
