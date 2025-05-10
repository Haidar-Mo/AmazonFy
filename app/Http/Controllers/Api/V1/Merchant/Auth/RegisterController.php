<?php

namespace App\Http\Controllers\Api\V1\Merchant\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\Auth\RegisterRequest;
use App\Models\{
    User
};
use App\Models\Wallet;
use App\Notifications\VerificationCodeNotification;
use App\Services\CodeService;
use App\Traits\{
    HasFiles,
    HasVerificationCode
};
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Notification;
use App\Notifications\verfication_code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use HasFiles, ResponseTrait;

    public function __construct(protected CodeService $codeService)
    {

    }
    public function register(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $user = User::create($request->validated());

            $verification_code = $this->codeService->getOrCreateVerificationCode($user->id);

            DB::afterCommit(function () use ($user, $verification_code) {
                Notification::send($user, new VerificationCodeNotification($user, $verification_code));
            });

            Wallet::create(['user_id' => $user->id]);

            //! $user->assignRole('merchant'); assign the merchant role after being documented by the admin

            // $accessToken = $user->createToken(
            //     'access_token',
            //     [TokenAbility::ACCESS_API->value, 'role:merchant'],
            //     Carbon::now()->addMinutes(config('sanctum.ac_expiration'))
            // );

            // $refreshToken = $user->createToken(
            //     'refresh_token',
            //     [TokenAbility::ISSUE_ACCESS_TOKEN->value],
            //     Carbon::now()->addMinutes(config('sanctum.rt_expiration'))
            // );

            return response()->json([
                'message' => 'User registered! Please check your email to verify your account.',
                'user' => $user,
                // 'access_token' => $accessToken->plainTextToken,
                // 'refresh_token' => $refreshToken->plainTextToken,
            ], 201);
        });
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'verification_code' => 'required'
        ]);
        return $this->codeService->verifyCode($request);
    }

    public function resendVerificationCode(Request $request)
    {
        return $this->codeService->resendCode($request);
    }


}
