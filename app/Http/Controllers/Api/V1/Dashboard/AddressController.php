<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class AddressController extends Controller
{
    use ResponseTrait, HasFiles;

    public function index()
    {
        try {
            return $this->showResponse(Address::all(), 'address.index_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'address.index_error', []);
        }
    }
    public function indexTypeIsStore()
    {
        try {
            return $this->showResponse(Address::where('type', 'store')->get(), 'address.index_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'address.index_error', []);
        }
    }
    public function indexTypeIsVisa()
    {
        try {
            return $this->showResponse(Address::where('type', 'visa')->get(), 'address.index_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'address.index_error', []);
        }
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'network_name' => [
                'required',
                'string',
                Rule::unique('addresses')->where(function ($query) use ($request) {
                    return $query->where('type', $request->type);
                })
            ],
            'target' => 'required|string',
            'qr_image' => 'required|image',
            'type' => 'required|string'
        ], __('messages.address.validation'));

        try {
            $data['qr_image'] = $this->saveFile($request->file('qr_image'), 'QR_addresses');

            $address = DB::transaction(function () use ($data) {
                return Address::create($data);
            });

            return $this->showResponse($address, 'address.store_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'address.store_error', []);
        }
    }

    public function update(string $id, Request $request)
    {
        try {
            $address = Address::findOrFail($id);

            $data = $request->validate([
                'network_name' => [
                    "sometimes",
                    Rule::unique('addresses')->where(function ($query) use ($request) {
                        return $query->where('type', $request->type);
                    })->ignore($address->id, 'id')
                ],
                'target' => 'sometimes|string',
                'qr_image' => 'sometimes|image',
                'type' => 'sometimes|string'
            ], __('messages.address.validation'));

            if ($request->hasFile('qr_image')) {
                $this->deleteFile($address->qr_image);
                $data['qr_image'] = $this->saveFile($request->file('qr_image'), 'QR_addresses');
            }

            DB::transaction(function () use ($address, $data) {
                $address->update($data);
            });

            return $this->showResponse($address, 'address.update_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'address.update_error', []);
        }
    }

    public function destroy(string $id)
    {
        try {
            $address = Address::findOrFail($id);

            DB::transaction(function () use ($address) {
                $address->delete();
            });

            return $this->showMessage('address.delete_success');
        } catch (\Exception $e) {
            return $this->showError($e, 'address.delete_error', []);
        }
    }
}