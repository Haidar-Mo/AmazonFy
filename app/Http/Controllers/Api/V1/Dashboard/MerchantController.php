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
                ->with('shop')
                ->where(function ($query) {
                    $query->whereRelation('shop', 'status', 'pending')
                        ->orWhereDoesntHave('shop');
                })
                ->latest()
                ->get()
                ->makeVisible(['is_blocked'])
                ->append(['verification_code', 'shop_status']);
            return $this->showResponse($merchants, 'merchants.fetched');

        } catch (\Exception $e) {
            return $this->showError($e, 'merchants.errors.errors.fetch_error');
        }
    }

    public function show(string $id)
    {
        try {
            $merchant = $this->service->show($id);
            return $this->showResponse($merchant, 'merchants.details_fetched');
        } catch (\Exception $e) {
            return $this->showError($e, 'merchants.errors.details_error');
        }
    }

    public function store(MerchantCreateRequest $request)
    {
        try {
            $merchant = $this->service->store($request);
            return $this->showResponse($merchant, 'merchants.created');
        } catch (\Exception $e) {
            return $this->showError($e, 'merchants.errors.create_error');
        }
    }

    public function update(MerchantUpdateRequest $request, string $id)
    {
        try {
            $merchant = $this->service->update($request, $id);
            return $this->showResponse($merchant, 'merchants.updated');
        } catch (\Exception $e) {
            return $this->showError($e, 'merchants.errors.update_error');
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->service->destroy($id);
            return $this->showMessage('merchants.deleted');
        } catch (\Exception $e) {
            return $this->showError($e, 'merchants.errors.delete_error');
        }
    }

    public function blockMerchant(string $id)
    {
        try {
            $merchant = $this->service->blockMerchant($id);
            return $this->showResponse($merchant, 'merchants.blocked');
        } catch (\Exception $e) {
            return $this->showError($e, 'merchants.errors.block_error');
        }
    }

    public function unblockMerchant(string $id)
    {
        try {
            $merchant = $this->service->unblockMerchant($id);
            return $this->showResponse($merchant, 'merchants.unblocked');
        } catch (\Exception $e) {
            return $this->showError($e, 'merchants.errors.unblock_error');
        }
    }

}
