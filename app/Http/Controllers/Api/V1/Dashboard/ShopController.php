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
            return $this->showResponse($shops, 'shop.index_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'shop.errors.index_error');
        }
    }

    public function show(string $id)
    {
        try {
            $shop = $this->service->show($id);
            return $this->showResponse($shop, 'shop.show_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'shop.errors.show_error');
        }
    }

    public function store(ShopCreateRequest $request)
    {
        try {
            $shop = $this->service->store($request);
            return $this->showResponse($shop, 'shop.store_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'shop.errors.store_error');
        }
    }

    public function update(ShopUpdateRequest $request, string $id)
    {
        try {
            $shop = $this->service->update($request, $id);
            return $this->showResponse($shop, 'shop.update_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'shop.errors.update_error');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('shop.delete_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'shop.errors.delete_error');
        }
    }

    public function activateShop(string $id)
    {
        try {
            $shop = $this->service->activateShop($id);
            return $this->showResponse($shop, 'shop.activate_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'shop.errors.activate_error');
        }
    }

    public function deactivateShop(string $id)
    {
        try {
            $shop = $this->service->deactivateShop($id);
            return $this->showResponse($shop, 'shop.deactivate_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'shop.errors.deactivate_error');
        }
    }
}
