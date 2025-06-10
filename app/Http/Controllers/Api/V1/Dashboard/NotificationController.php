<?php

namespace App\Http\Controllers\Api\V1\dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\NotificationRequest;
use App\Models\User;
use App\Notifications\ToMerchantNotification;
use App\Services\Dashboard\NotificationService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isInstanceOf;

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
            return $this->showResponse($notifications, 'تم جلب كل الإشعارات بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب كل الإشعارات');
        }
    }

    public function indexReceived()
    {
        try {
            $notifications = $this->service->indexReceived();
            return $this->showResponse($notifications, 'تم جلب كل الإشعارات بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب كل الإشعارات');
        }
    }

    public function show(string $id)
    {
        try {
            $notification = $this->service->show($id);
            return $this->showResponse($notification, 'تم جلب الإشعار بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب كل الإشعارات');
        }
    }


    public function store(NotificationRequest $request, string $id)
    {
        try {
            $this->service->store($request, $id);
            return $this->showMessage('تم إرسال الإشعار بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء إرسال الإشعار');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('تم حذف الإشعار بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء حذف الإشعار');
        }
    }

    public function countNotification()
    {
        $user = auth()->user();
        $notification_count = $user->notifications->count();
        return $this->showResponse($notification_count);
    }
}
