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
            $regions = ProductType::where('locale', '=', app()->getLocale())->get();
            return $this->showResponse($regions, 'product_type.index_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product_type.errors.index_error');
        }
    }

    public function show(string $id)
    {
        try {
            $regions = ProductType::where('locale', '=', app()->getLocale())->findOrFail($id);
            return $this->showResponse($regions, 'product_type.show_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product_type.errors.show_error');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string'
            ]);
            $region = ProductType::create($data);
            return $this->showResponse($region, 'product_type.create_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product_type.errors.create_error');
        }
    }

    public function localeStore(Request $request)
    {
        try {
            $request->validate([
                'types' => 'required|array',
                'types.*.locale' => 'required',
                'types.*.name' => 'required|string'
            ]);
            foreach ($request->types as $type) {
                $types[] = ProductType::create([
                    'locale' => $type['locale'],
                    'name' => $type['name']
                ]);
            }
            return $this->showResponse($types, 'product_type.create_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'product_type.errors.create_error');
        }
    }

    public function update(Request $request, string $id)
    {
        try {
            $data = $request->validate([
                'name' => 'sometimes|string'
            ]);
            $region = ProductType::findOrFail($id);
            $region->update($data);
            return $this->showResponse($region, 'product_type.update_success');
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
