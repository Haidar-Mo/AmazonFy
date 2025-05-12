<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        try {
            $regions = ProductType::all();
            return $this->showResponse($regions, 'تم جلب كل الفئات بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب كل الفئات');
        }
    }
    public function show(string $id)
    {
        try {
            $regions = ProductType::findOrFail($id);
            return $this->showResponse($regions, 'تم جلب كل الفئة بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب معلومات الفئة');
        }

    }
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string'
            ]);
            $region = ProductType::create($data);
            return $this->showResponse($region, 'تم إضافة فئة جديدة');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء إضافة فئة جديدة');
        }

    }
    public function update(request $request, string $id)
    {
        try {
            $data = $request->validate([
                'name' => 'sometimes|string'
            ]);
            $region = ProductType::findOrFail($id);
            $region->update($data);
            return $this->showResponse($region, 'تم تعديل الفئة بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء تعديل الفئة');
        }

    }
    public function destroy(string $id)
    {
        try {
            ProductType::findOrFail($id)->delete();
            return $this->showMessage('تم حذف الفئة بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء حذف الفئة');
        }
    }
}
