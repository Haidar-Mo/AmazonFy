<?php
namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\NotificationRequest;
use App\Services\Dashboard\NotificationService;
use App\Traits\ResponseTrait;

class NotificationController extends Controller
{
    use ResponseTrait;

    public function __construct(public NotificationService $service)
    {
    }

    public function indexSended()
    {
        try {
            $notifications = $this->service->indexSended();
            return $this->showResponse($notifications, 'notification.index_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'notification.errors.index_error');
        }
    }

    public function indexReceived()
    {
        try {
            $notifications = $this->service->indexReceived();
            return $this->showResponse($notifications, 'notification.index_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'notification.errors.index_error');
        }
    }

    public function show(string $id)
    {
        try {
            $notification = $this->service->show($id);
            return $this->showResponse($notification, 'notification.show_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'notification.errors.show_error');
        }
    }

    public function store(NotificationRequest $request, string $id)
    {
        try {
             $this->service->store($request, $id);
            return $this->showMessage('notification.create_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'notification.errors.create_error');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('notification.delete_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'notification.errors.delete_error');
        }
    }

    public function countNotification()
    {
        $user = auth()->user();
        $notification_count = $user->unreadNotifications()->count();
        return $this->showResponse($notification_count, 'notification.count_success');
    }
}
