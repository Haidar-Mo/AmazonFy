<?php

namespace App\Http\Controllers\Api\V1\Dashboard\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\LoginRequest;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::role(['admin', 'supervisor'])->where('phone_number', '=', $data['phone_number'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value, "role:$user->role"],
            Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
        );

        $refreshToken = $user->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        );

        return response()->json([
            'message' => 'User logged in successfully.',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'user' => $user,
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function refreshToken(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value, "role:$user->role"],
            Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
        );

        $refreshToken = $user->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        );
        return response([
            'message' => 'Success',
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
            'user' => $user,
        ]);
    }


}
