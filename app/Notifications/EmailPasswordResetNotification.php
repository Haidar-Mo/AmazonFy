<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailPasswordResetNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user,
        public string $verificationCode
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $locale = method_exists($notifiable, 'preferredLocale')
            ? $notifiable->preferredLocale()
            : config('app.locale');

        return (new MailMessage)
            ->mailer('smtp')
            ->locale($locale)
            ->subject(trans('notifications.email_password_reset.subject', [], $locale))
            ->greeting(trans('notifications.email_password_reset.greeting', ['name' => $this->user->first_name], $locale))
            ->line(trans('notifications.email_password_reset.line_1', ['code' => $this->verificationCode], $locale))
            ->line(trans('notifications.email_password_reset.line_2', [], $locale));
    }

    public function toArray($notifiable): array
    {
        return []; // Optional if you don't log this notification
    }
}
