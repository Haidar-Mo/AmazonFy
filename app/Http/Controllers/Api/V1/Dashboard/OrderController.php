<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ShopOrder;
use App\Traits\ResponseTrait;

class OrderController extends Controller
{

    use ResponseTrait;

    public function show(string $id)
    {
        try {
            $order = ShopOrder::with(['shop', 'product'])->findOrFail($id)
                ->append([
                    'shop_name',
                    'merchant_name',
                    'client_name',
                    'client_email',
                    'client_phone_number',
                    'client_address',
                    'client_region',
                    'created_from'
                ]);
                $order->product->append('full_path_image');
            return $this->showResponse($order, 'تم عرض تفاصيل الطلب بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء عرض تفاصيل الطلب');
        }
    }

    public function index()
    {
        try {
            $orders = ShopOrder::with(['shop', 'product'])->get()->append([
                'shop_name',
                'merchant_name',
                'client_name',
                'client_email',
                'client_phone_number',
                'client_address',
                'client_region',
                'created_from',
            ]);
            $orders->each(function ($order){
                $order->product->append('full_path_image');
            });
            return $this->showResponse($orders, 'تم جلب كل الطلبات بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء جلب الطلبات');
        }
    }

    public function destroy(string $id)
    {
        try {
            ShopOrder::findOrFail($id)->delete();
            return $this->showMessage('تم حذف الطلب بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء حذف الطلب');
        }
    }
}
