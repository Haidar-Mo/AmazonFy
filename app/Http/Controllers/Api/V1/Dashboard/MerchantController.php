<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\MerchantCreateRequest;
use App\Http\Requests\Dashboard\MerchantUpdateRequest;
use App\Models\User;
use App\Services\Dashboard\MerchantService;
use App\Traits\ResponseTrait;

class MerchantController extends Controller
{
    use ResponseTrait;

    public function __construct(public MerchantService $service)
    {
    }

    public function index()
    {
        try {
            $merchants = User::role('merchant', 'api')
                ->get()
                ->append(['shop_status']);
            return $this->showResponse($merchants, 'تم جلب كل التجار');

        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب كل التجار ');
        }
    }

    public function show(string $id)
    {
        try {
            $merchant = User::with('shop')
                ->role('merchant', 'api')
                ->findOrFail($id)
                ->append(['shop_status']);
            if ($merchant->shop) {
                $merchant->shop->append(['logo_full_path', 'identity_front_face_full_path', 'identity_back_face_full_path']);
            }
            return $this->showResponse($merchant, 'تم جلب معلومات التاجر');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء عرض بيانات التاجر');
        }
    }

    public function store(MerchantCreateRequest $request)
    {
        try {
            $merchant = $this->service->store($request);
            return $this->showResponse($merchant, 'تم إنشاء التاجر بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء إنشاء تاجر جديد ');
        }
    }


    public function update(MerchantUpdateRequest $request, string $id)
    {
        try {
            $merchant = $this->service->update($request, $id);
            return $this->showResponse($merchant, 'تم تعديل معلومات التاجر بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء تعديل بيانات التاجر');
        }
    }


    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('تم حذف التاجر بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء حذف التاجر');
        }
    }
}
