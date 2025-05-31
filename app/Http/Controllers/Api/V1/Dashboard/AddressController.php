<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Traits\HasFiles;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    use ResponseTrait, HasFiles;

    public function index()
    {
        try {
            return $this->showResponse(Address::all());
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء جلب كل العناوين');
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'network_name' => 'string|required|unique:addresses,network_name',
            'target' => 'required|string',
            'qr_image' => 'required|image'
        ]);

        try {
            $data['qr_image'] = $this->saveFile($request->file('qr_image'), 'QR_addresses');
            $address = DB::transaction(function () use ($data) {
     
                return Address::create($data);
     
            });
            return $this->showResponse($address, 'تم إضافة العنوان بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء إضافة العنوان');
        }
    }



    public function update(string $id, Request $request)
    {
        try {
            $address = Address::findOrFail($id);
            $data = $request->validate([
                'network_name' => "sometimes|unique:addresses,network_name,except,$address->id",
                'target' => 'sometimes|string',
                'qr_image' => 'sometimes|image'

            ]);
            if ($request->hasFile('qr_image')) {
                $this->deleteFile($address->qr_image);
                $data['qr_image'] = $this->saveFile($request->file('qr_image'), 'QR_addresses');
            }
            DB::transaction(function () use ($address, $data) {
                $address->update($data);
            });
            return $this->showResponse($address, 'تم تعديل معلومات العنوان');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء تعديل العنوان');
        }
    }

    public function destroy(string $id)
    {
        try {
            $address = Address::findOrFail($id);
            DB::transaction(function () use ($address) {
                $address->delete();
            });
            return $this->showMessage('تم حذف العنوان بنجاح');
        } catch (\Exception $e) {
            return $this->showError($e, 'حدث خطأ أثناء حذف العنوان');
        }
    }
}
