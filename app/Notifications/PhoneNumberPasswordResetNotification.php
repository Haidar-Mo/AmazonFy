<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class PhoneNumberPasswordResetNotification extends BaseNotification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $user, public string $verificationCode)
    {
        $this->notType = 'phone_password_reset';

        $this->body = [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'phone' => $this->user->phone,
            ],
            'verification_code' => $this->verificationCode,
        ];
    }

    public function via($notifiable): array
    {
        return ['database'];
    }
}
