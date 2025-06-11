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
        try {
            $storehouses = Storehouse::all()->append(['region_name']);
            return $this->showResponse($storehouses, 'storehouse.index_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'storehouse.errors.index_error');
        }
    }

    public function store(StorehouseCreateRequest $request)
    {
        try {
            $storehouse = $this->service->store($request);
            return $this->showResponse($storehouse, 'storehouse.store_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'storehouse.errors.store_error');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('storehouse.delete_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'storehouse.errors.delete_error');
        }
    }
}
