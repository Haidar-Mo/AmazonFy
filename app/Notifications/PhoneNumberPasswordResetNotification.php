<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class PhoneNumberPasswordResetNotification extends BaseNotification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public User $user, public string $verificationCode)
    {
        $this->notType = 'phone_number_password_reset';
        $this->title = __('notifications.phone_number_password_reset.title') . $user->name . ' - ID: ' . $user->id;
        $this->body = __('notifications.phone_verification_code.body') . $this->verificationCode;
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
