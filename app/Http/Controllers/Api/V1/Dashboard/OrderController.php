<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ShopOrder;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    use ResponseTrait;


    public function show(string $id)
    {
        try {
            $order = ShopOrder::with(['shop', 'items.product'])->findOrFail($id);
            return $this->showResponse($order, 'تم عرض تفاصيل الطلب بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء عرض تفاصيل الطلب');
        }
    }

    public function index()
    {
        try {
            $orders = ShopOrder::all()->append(['shop_name']);
            return $this->showResponse($orders, 'تم جلب كل الطلبات بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء جلب الطلبات');
        }
    }

    public function destroy(string $id)
    {
    }
}
