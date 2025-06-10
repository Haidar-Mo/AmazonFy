<?php

namespace App\Services\Dashboard;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProfileService
{

    public function update(Request $request)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email',
            'phone_number' => 'sometimes|string|unique:users,phone_number',
            'password' => 'sometimes|string',
        ]);
        if (array_key_exists('password', $data))
            $data['password'] = bcrypt($data['password']);
        $user = auth()->user();
        return DB::transaction(function () use ($user, $data) {

            $user->update($data);
            return $user;
        });

    }
}
