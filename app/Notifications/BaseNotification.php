<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

abstract class BaseNotification extends Notification
{
    use Queueable;

    protected string $notType;
    protected string $title;
    protected array $body;

    public function toArray($notifiable)
    {
        $locale = method_exists($notifiable, 'preferredLocale')
            ? $notifiable->preferredLocale()
            : config('app.locale');

        return [
            'not_type' => $this->notType,
            'title' => $this->translate('title', $locale),
            'body' => $this->body,
        ];
    }

    public function toFirebase()
    {
        return [
            'notification' => [
                'title' => $this->title,
                'body' => $this->generateFirebaseBody($this->body),
            ],
            'data' => $this->toArray(null),
        ];
    }

    public function toPusher()
    {
        return $this->toArray(null);
    }



    protected function generateFirebaseBody(array $body): string
    {
        // You can customize how to flatten the body array for Firebase
        return $this->title;
    }

    protected function translate(string $key, string $locale): string
    {
        return trans("notifications.{$this->notType}.{$key}", [], $locale);
    }
}
