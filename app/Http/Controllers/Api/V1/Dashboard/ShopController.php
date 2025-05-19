<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ShopCreateRequest;
use App\Http\Requests\Dashboard\ShopUpdateRequest;
use App\Services\Dashboard\ShopService;
use App\Traits\ResponseTrait;

class ShopController extends Controller
{
    use ResponseTrait;

    public function __construct(public ShopService $service)
    {
    }


    public function index()
    {
        try {
            $shops = $this->service->index();
            return $this->showResponse($shops, 'تم جلب المتاجر بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب المتاجر');
        }

    }


    public function show(string $id)
    {
        try {
            $shop = $this->service->show($id);
            return $this->showResponse($shop, 'تم جلب تفاصيل المتجر بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء إنشاء متجر');
        }
    }


    public function store(ShopCreateRequest $request)
    {
        try {
            $shop = $this->service->store($request);
            return $this->showResponse($shop, 'تم إنشاء المتجر بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء إنشاء متجر');
        }
    }

    public function update(ShopUpdateRequest $request, string $id)
    {
        try {
            $shop = $this->service->update($request, $id);
            return $this->showResponse($shop, 'تم تعديل بيانات المتجر بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء تعديل بيانات متجر');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('تم حذف المتجر بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء حذف المتجر');
        }
    }

    public function activateShop(string $id)
    {
        try {
            $shop = $this->service->activateShop($id);
            return $this->showResponse($shop, 'تم تفعيل المتجر بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء تفعيل المتجر');
        }
    }

    public function deactivateShop(string $id)
    {
        try {
            $shop = $this->service->deactivateShop($id);
            return $this->showResponse($shop, 'تم إلغاء تفعيل المتجر بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء إلغاء تفعيل المتجر');
        }
    }
}
