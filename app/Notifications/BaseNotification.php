<?php

namespace App\Notifications;

use App\Models\User;
use App\Traits\FirebaseNotificationTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    use Queueable;

    protected string $notType;
    protected string $title;
    protected string $body;
    protected Model $model;

    public function toArray($notifiable)
    {
        return [
            'en' => [
                'notification_type' => $this->notType,
                'title' => $this->translate('title', 'en'),
                'body' => $this->body ?? $this->translate('body', 'en'),
                'model_id' => $this->model->id ?? null
            ],
            'ar' => [
                'notification_type' => $this->notType,
                'title' => $this->translate('title', 'ar'),
                'body' => $this->body ?? $this->translate('body', 'ar'),
                'model_id' => $this->model->id ?? null
            ]
        ];
    }

    public function toPusher()
    {
        return $this->toArray(null);
    }


    protected function translate(string $key, string $locale): string
    {
        return trans("notifications.{$this->notType}.{$key}", [], $locale);
    }
}
