<?php

namespace App\Http\Controllers\Api\V1\Merchant\Auth;

use App\Enums\TokenAbility;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\Auth\RegisterRequest;
use App\Models\{
    User
};
use App\Models\Wallet;
use App\Traits\HasFiles;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Notification;
use App\Notifications\verfication_code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    use HasFiles;

    public function register(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request) {

            $user = User::create($request->validated());

            $user->sendEmailVerificationNotification();

            Wallet::create(['user_id' => $user->id]);

            // $user->assignRole('merchant');

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

            return response()->json([
                'message' => 'User registered! Please check your email to verify your account.',
                'user' => $user,
            ], 201);
        });
    }

    public function verifyEmail(Request $request)
    {
        $user = User::find($request->route('id'));

        if (!hash_equals((string) $request->route('hash'), sha1($user->email))) {
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email verified successfully']);
    }

    // public function resend(Request $request)
    // {
    //     return DB::transaction(function () use ($request) {
    //         $student = Auth::user();
    //         $verificationCode = $this->getOrCreateVerificationCode($student->email, 'check-email');
    //         Notification::route('mail', $student->email)
    //             ->notify(new verfication_code($student, $verificationCode));
    //         return $this->sudResponse('Code has been resent');
    //     });
    // }


    // public function verify(Request $request)
    // {
    //     $request->validate([
    //         'verification_code' => 'required'
    //     ]);
    //     return $this->verifyCode($request['verification_code']);

    // }

}
