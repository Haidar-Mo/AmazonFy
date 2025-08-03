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

    protected string $notType; //:[ user, shop, order, transaction, custom_notification , chat_message ]
    protected string $title;
    protected string $body;
    protected Model $model;
    protected string $notification_name;

    public function toArray($notifiable)
    {
        return [
            'en' => [
                'notification_type' => $this->notType,
                'title' => $this->title ?? $this->translate('title', 'en'),
                'body' => $this->body ?? $this->translate('body', 'en'),
                'model_id' => $this->model->id ?? null
            ],
            'ar' => [
                'notification_type' => $this->notType,
                'title' => $this->title ?? $this->translate('title', 'ar'),
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
        return trans("notifications.{$this->notification_name}.{$key}", [], $locale);
    }
}
