<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\ProductCreateRequest;
use App\Http\Requests\Dashboard\ProductUpdateRequest;
use App\Services\Dashboard\ProductService;
use App\Traits\ResponseTrait;

class ProductController extends Controller
{

    use ResponseTrait;

    public function __construct(public ProductService $service)
    {
    }

    public function index()
    {
        try {
            $products = $this->service->index();
            return $this->showResponse($products, 'تم جلب كل المنتجات');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب كل المنتجات');
        }
    }

    public function show(string $id)
    {
        try {
            $product = $this->service->show($id);
            return $this->showResponse($product, 'تم جلب المنتج');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء عرض تفاصيل المنتج');
        }
    }

    public function store(ProductCreateRequest $request)
    {
        try {
            $product = $this->service->store($request);
            return $this->showResponse($product, 'تم إنشاء المنتج');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء حفظ المنتج');
        }
    }

    public function update(ProductUpdateRequest $request, string $id)
    {
        try {
            $product = $this->service->update($request, $id);
            return $this->showResponse($product, 'تم تعديل المنتج');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء تعديل المنتج');
        }
    }


    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('تم حذف المنتج');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء حذف المنتج');
        }
    }
}
