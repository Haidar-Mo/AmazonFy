<?php

namespace App\Http\Controllers\Api\V1\Merchant\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\Auth\LoginRequest;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = User::when($request->email, function ($query) use ($request) {
            return $query->where('email', $request->email);
        }, function ($query) use ($request) {
            return $query->where('phone_number', $request->phone_number);
        })
            ->first();

        // Check credentials
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if (!$user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email not verified'], 403);
        }

        if ($request->has('deviceToken')) {
            $user->update(['deviceToken' => $request->deviceToken]);
        }

        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value, 'role:merchant'],
            Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
        );

        $refreshToken = $user->createToken(
            'refresh_token',
            [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
        );

        if (($user->shop->status ?? null) == 'active') {
            $user->is_documented = true; // Assuming 'is_documented' can be added as a dynamic property
        } else {
            $user->is_documented = false;
        }

        // Remove the 'shop' property
        unset($user->shop);

        return response()->json([
            'message' => 'User logged in successfully.',
            'user' => $user,
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function refreshToken(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $accessToken = $user->createToken(
            'access_token',
            [TokenAbility::ACCESS_API->value, 'role:merchant'],
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
