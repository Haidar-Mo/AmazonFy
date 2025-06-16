<?php
namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ShopType;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopTypesController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        try {
            $types = ShopType::where('locale', '=', app()->getLocale())->get();
            return $this->showResponse($types, 'shop_type.index_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'shop_type.errors.index_error');
        }
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $request->validate([
                    'name' => ['required', 'string']
                ]);

                $type = ShopType::create([
                    'name' => $request->name
                ]);

                return $this->showResponse($type, 'shop_type.store_success');
            } catch (\Exception $e) {
                return $this->showError($e, 'shop_type.errors.store_error');
            }
        });
    }

    public function localeStore(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                $request->validate([
                    'types' => 'required|array',
                    'types.*.locale' => ['required', 'string'],
                    'types.*.name' => ['required', 'string']
                ]);
                foreach ($request->types as $type) {
                    $types[] = ShopType::create([
                        'locale' => $type['locale'],
                        'name' => $type['name']
                    ]);
                }
                return $this->showResponse($types, 'shop_type.store_success');
            } catch (\Exception $e) {
                return $this->showError($e, 'shop_type.errors.store_error');
            }
        });
    }

    public function show(ShopType $shopType)
    {
        try {
            return $this->showResponse($shopType, 'shop_type.show_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'shop_type.errors.show_error');
        }
    }

    public function update(Request $request, ShopType $shopType)
    {
        return DB::transaction(function () use ($request, $shopType) {
            try {
                $request->validate([
                    'name' => ['required', 'string']
                ]);

                $shopType->update([
                    'name' => $request->name
                ]);

                return $this->showResponse($shopType, 'shop_type.update_success');
            } catch (\Exception $e) {
                return $this->showError($e, 'shop_type.errors.update_error');
            }
        });
    }

    public function destroy(ShopType $shopType)
    {
        return DB::transaction(function () use ($shopType) {
            try {
                $shopType->delete();
                return $this->showMessage('shop_type.delete_success');
            } catch (\Exception $e) {
                return $this->showError($e, 'shop_type.errors.delete_error');
            }
        });
    }
}
