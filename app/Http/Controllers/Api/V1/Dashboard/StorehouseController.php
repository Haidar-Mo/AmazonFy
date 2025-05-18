<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\StorehouseCreateRequest;
use App\Models\Storehouse;
use App\Services\Dashboard\StorehouseService;
use App\Traits\ResponseTrait;

class StorehouseController extends Controller
{
    use ResponseTrait;

    public function __construct(public StorehouseService $service)
    {
    }

    public function index()
    {
        return $this->showResponse(Storehouse::all()->append(['region_name']), 'تم جلب كل المستودعات بنجاح', 200);
    }


    public function store(StorehouseCreateRequest $request)
    {
        try {
            $storehouse = $this->service->store($request);
            return $this->showResponse($storehouse, 'تم إنشاء المستودع بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء إنشاء المستودع');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('تم حذف المستودع بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء حذف المستودع');
        }
    }
}
