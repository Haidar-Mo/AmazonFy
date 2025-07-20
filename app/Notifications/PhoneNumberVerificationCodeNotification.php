<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class PhoneNumberVerificationCodeNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user, public string $verificationCode)
    {
        $this->notType = 'phone_verification_code';
        $this->body = trans('notifications.phone_verification_code.body', ['code' => $this->verificationCode]);
        $this->model = $user;
        $this->notification_name = "phone_verification_code";

    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
