<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        try {
            $regions = Region::with(['children'])->where('parent_id', null)->get();
            return $this->showResponse($regions, 'تم جلب كل المناطق بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب كل المناطق');
        }
    }
    public function show(string $id)
    {
        try {
            $regions = Region::with(['children'])->findOrFail($id);
            return $this->showResponse($regions, 'تم جلب كل المنطقة بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء جلب معلومات المنطقة');
        }

    }
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'parent_id' => 'nullable|exists:regions,id',
                'name' => 'required|string'
            ]);
            $region = Region::create($data);
            return $this->showResponse($region, 'تم إضافة منطقة جديدة');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء إضافة منطقة جديدة');
        }

    }
    public function update(request $request, string $id)
    {
        try {
            $data = $request->validate([
                'parent_id' => 'sometimes|exists:regions,id',
                'name' => 'sometimes|string'
            ]);
            $region = Region::findOrFail($id);
            $region->update($data);
            return $this->showResponse($region, 'تم تعديل المنطقة بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء تعديل المنطقة');
        }

    }
    public function destroy(string $id)
    {
        try {
            Region::findOrFail($id)->delete();
            return $this->showMessage('تم حذف المنطقة بنجاح', 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ ما أثناء حذف المنطقة');
        }

    }


}
