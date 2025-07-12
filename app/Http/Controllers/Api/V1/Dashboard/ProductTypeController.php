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
            $types = ProductType::all();
            return $this->showResponse($types, 'product_type.index_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product_type.errors.index_error');
        }
    }

    public function show(string $id)
    {
        try {
            $types = ProductType::findOrFail($id);
            return $this->showResponse($types, 'product_type.show_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product_type.errors.show_error');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name_en' => 'required_without:name_ar|string',
                'name_ar' => 'required_without:name_en|string'
            ]);
            $type = ProductType::create();
            if (isset($data['name_ar'])) {
                $type->translateOrNew('ar')->name = $data['name_ar'];
            }
            if (isset($data['name_en'])) {
                $type->translateOrNew('en')->name = $data['name_en'];
            }
            return $this->showResponse($type, 'product_type.create_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product_type.errors.create_error');
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'name_ar' => 'sometimes|string',
                'name_en' => 'sometimes|string'
            ]);
            $type = ProductType::findOrFail($id);

            if (isset($data['name_ar'])) {
                $type->translateOrNew('ar')->name = $data['name_ar'];
            }
            if (isset($data['name_en'])) {
                $type->translateOrNew('en')->name = $data['name_en'];
            }
            $type->save();
            return $this->showResponse($type, 'product_type.update_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product_type.errors.update_error');
        }
    }

    public function destroy(string $id)
    {
        try {
            ProductType::findOrFail($id)->delete();
            return $this->showMessage('product_type.delete_success', [], 200);
        } catch (\Exception $e) {
            return $this->showError($e, 'product_type.errors.delete_error');
        }
    }
}
