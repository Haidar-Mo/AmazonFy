<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\OrderService;
use App\Traits\ResponseTrait;

class OrderController extends Controller
{

    use ResponseTrait;

    public function __construct(public OrderService $service)
    {
    }

    public function show(string $id)
    {
        try {
            $order = $this->service->show($id);
            return $this->showResponse($order, 'تم عرض تفاصيل الطلب بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء عرض تفاصيل الطلب');
        }
    }

    public function index()
    {
        try {
            $orders = $this->service->index();
            return $this->showResponse($orders, 'تم جلب كل الطلبات بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء جلب الطلبات');
        }
    }

    public function update(string $id)
    {
        try {
            $order = $this->service->updateStatus($id);
            return $this->showResponse($order, 'تم تعديل حالة الطلب بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء تعديل حالة الطلب');
        }
    }

    public function cancelOrder(string $id)
    {
        try {
            $order = $this->service->cancelOrder($id);
            return $this->showResponse($order, 'تم إلغاء الطلب بنجاح');

        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء إلغاء الطلب');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('تم حذف الطلب بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء حذف الطلب');
        }
    }
}
