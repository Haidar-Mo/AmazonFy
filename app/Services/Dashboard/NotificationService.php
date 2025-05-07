<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Notifications\ToMerchantNotification;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;

/**
 * Class NotificationService.
 */
class NotificationService
{


    public function index()
    {
        $notifications = DatabaseNotification::orderBy('created_at', 'asc')
            ->get()
            ->map(function ($notification) {
                $notifiable = $notification->notifiable;
                $notification->target_name = $notifiable->name ?? null;
                return $notification;
            });
        return $notifications->makeHidden(['notifiable']);
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
        Notification::send($user, new ToMerchantNotification($data['title'], $data['content']));
    }


    public function destroy(string $id)
    {
        DatabaseNotification::findOrFail($id)->delete();
    }
}
