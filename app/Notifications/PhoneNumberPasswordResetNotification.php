<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PhoneNumberPasswordResetNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public string $verificationCode)
    {
        $this->notType = 'phone_number_password_reset';
        $this->body = trans('notifications.phone_verification_code.body', ['code' => $this->verificationCode]);
        $this->model = $user;
        $this->notification_name = 'phone_number_password_reset';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }
}
