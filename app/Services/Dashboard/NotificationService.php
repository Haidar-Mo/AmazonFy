<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Notifications\ToMerchantNotification;
use App\Traits\FirebaseNotificationTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;

/**
 * Class NotificationService.
 */
class NotificationService
{
    use FirebaseNotificationTrait;

    public function indexSended()
    {
        $notifications = DatabaseNotification::where('type', ToMerchantNotification::class)
            ->latest()
            ->get()
            ->map(function ($notification) {
                $notifiable = $notification->notifiable;
                $notification->target_name = $notifiable->name ?? null;
                return $notification;
            });
        return $notifications->makeHidden(['notifiable']);
    }

    public function indexReceived()
    {
        $user = request()->user();
        $user->unreadNotifications->markAsRead();
        return $user->notifications;
    }

    public function show(string $id)
    {
        $notification = DatabaseNotification::where('id', $id)
            ->get()
            ->map(function ($notification) {
                $notifiable = $notification->notifiable;
                $notification->target_name = $notifiable->name ?? null;
                return $notification;
            })
            ->first();
        return $notification;
    }

    public function store(FormRequest $request, string $id)
    {
        $data = $request->validated();
        $user = User::findOrFail($id);
        Notification::send($user, new ToMerchantNotification($data['content']));
        if ($user->device_token) {
            $this->unicast($request, $user->device_token);
        }
    }

    public function destroy(string $id)
    {
        DatabaseNotification::findOrFail($id)->delete();
    }
}
