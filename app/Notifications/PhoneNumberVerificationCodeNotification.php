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

        $this->body = [
            'user' => $this->user,
            'verification_code' => $this->verificationCode,
        ];
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
