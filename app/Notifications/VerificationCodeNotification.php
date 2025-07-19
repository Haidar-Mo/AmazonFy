<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerificationCodeNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user, public string $verificationCode)
    {
        $this->notType = 'email_verification_code';
        $this->model = $user;
        /* $this->body = [
            'user' => $this->user,
            'verification_code' => $this->verificationCode,
        ]; */
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('notifications.email_verification_code.title'))
            ->greeting(__('notifications.email_verification_code.greeting', ['name' => $this->user->name]))
            ->line(__('notifications.email_verification_code.line_1', ['code' => $this->verificationCode]))
            ->line(__('notifications.email_verification_code.line_2'));
    }
}
