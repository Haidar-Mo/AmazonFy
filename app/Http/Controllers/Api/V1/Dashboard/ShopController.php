<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ShopCreateRequest;
use App\Http\Requests\Dashboard\ShopUpdateRequest;
use App\Models\Shop;
use App\Services\Dashboard\ShopService;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    use ResponseTrait;

    public function __construct(public ShopService $service)
    {
    }


    public function index()
    {
        $shops = Shop::all();
        return $this->showResponse($shops, 'تم جلب كل المتاجر بنجاح');
    }


    public function show(string $id)
    {
        $shop = Shop::with(['user'])->findOrFail($id)
            ->append([
                'logo_full_path',
                'identity_front_face_full_path',
                'identity_back_face_full_path',
            ]);
        return $this->showResponse($shop, 'تم جلب تفاصيل المتجر بنجاح');
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
}
