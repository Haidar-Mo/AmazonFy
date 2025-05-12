<?php

namespace App\Http\Controllers\Api\V1\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function ShopStore(FormRequest $request)
    {
        $data = $request->validated();
        $user = User::findOrFail($data['user_id']);
        if ($user->shop()->first()) {
            throw new \Exception('لا يمكن إنشاء أكثر من متجر لنفس المستخدم', 400);
        }
        $data['status'] = 'active';
        $data['logo'] = $this->saveFile($request->file('logo'), 'Images/Shops/Logo');
        $data['identity_front_face'] = $this->saveFile($request->file('identity_front_face'), 'Images/Shops/Identity');
        $data['identity_back_face'] = $this->saveFile($request->file('identity_back_face'), 'Images/Shops/Identity');
        return DB::transaction(function () use ($data, $user, $request) {
            $shop = $user->shop()->create($data);
            return $shop;
        });
    }
}
