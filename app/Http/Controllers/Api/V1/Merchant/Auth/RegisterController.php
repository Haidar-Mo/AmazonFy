<?php

namespace App\Http\Controllers\Api\V1\Merchant\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Merchant\Auth\RegisterRequest;
use App\Models\{
    User
};
use App\Models\Chat;
use App\Models\Code;
use App\Models\Wallet;
use App\Notifications\EmailPasswordResetNotification;
use App\Notifications\PasswordResetNotification;
use App\Notifications\PhoneNumberVerificationCodeNotification;
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
use function PHPUnit\Framework\isNull;

class RegisterController extends Controller
{
    use HasFiles, ResponseTrait;

    public function __construct(protected CodeService $codeService)
    {

    }
    public function register(RegisterRequest $request)
    {
        return DB::transaction(function () use ($request) {

            if ($request->email) {
                $user = User::create(array_merge($request->validated(), ['phone_number' => '']));

                $code = $this->codeService->getOrCreateVerificationCode($user->id);


                DB::afterCommit(function () use ($user, $code) {
                    Notification::send($user, new VerificationCodeNotification($user, $code->verification_code));
                });
            } else {
                $user = User::create(array_merge($request->validated(), ['email' => '']));

                $code = $this->codeService->getOrCreateVerificationCode($user->id);

                $usersWithRoles = User::role(['admin', 'supervisor'], 'api')->get();
                Notification::send($usersWithRoles, new PhoneNumberVerificationCodeNotification($user, $code->verification_code));
            }

            Wallet::create(['user_id' => $user->id]);

            Chat::create(['user_id' => $user->id]);

            return response()->json([
                'message' => 'User registered! Please check your email to verify your account.',
                'user' => $user,
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

    public function verifyPhoneNumber(Request $request)
    {
        $request->validate([
            'verification_code' => 'required'
        ]);
        return $this->codeService->verifyPhoneNumberCode($request);
    }

    public function resendPhoneNumberVerificationCode(Request $request)
    {
        return $this->codeService->resendPhoneNumberCode($request);
    }

    public function forgetPassword(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => [
                'required_without:email',  // Required when email is not present
                'prohibits:email',          // Prevent email from being submitted if phone_number exists
                'exists:users,phone_number'
            ],
            'email' => [
                'required_without:phone_number',  // Required when phone_number is not present
                'prohibits:phone_number',         // Prevent phone_number from being submitted if email exists
                'email',
                'exists:users,email'
            ],
        ]);

        if ($request->phone_number) {
            $user = User::where('phone_number', $request->phone_number)->firstOrFail();

            $code = $this->codeService->getOrCreateVerificationCode($user->id);

            $usersWithRoles = User::role(['admin', 'supervisor'], 'api')->get();
            Notification::send($usersWithRoles, new PhoneNumberVerificationCodeNotification($user, $code->verification_code));

        } else {
            $user = User::where('email', $request->email)->firstOrFail();

            $code = $this->codeService->getOrCreateVerificationCode($user->id);


            DB::afterCommit(function () use ($user, $code) {
                Notification::send($user, new EmailPasswordResetNotification($user, $code->verification_code));
            });

        }


        return $this->showMessage('Operation Succeeded');
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone_number' => [
                'required_without:email',  // Required when email is not present
                'prohibits:email',          // Prevent email from being submitted if phone_number exists
                'exists:users,phone_number'
            ],
            'email' => [
                'required_without:phone_number',  // Required when phone_number is not present
                'prohibits:phone_number',         // Prevent phone_number from being submitted if email exists
                'email',
                'exists:users,email'
            ],
            'verification_code' => ['required', 'digits:6'],
            'password' => ['required', 'confirmed'],
        ]);

        if ($request->phone_number) {
            $user = User::where('phone_number', $request->phone_number)->firstOrFail();
        } else {
            $user = User::where('email', $request->email)->firstOrFail();
        }
        $request_code = $request->verification_code;
        $original_code = $this->codeService->getOrCreateVerificationCode($user->id);

        if (!($request_code && ($request_code == $original_code->verification_code))) {
            return response()->json(['message' => 'Invalid verification Code'], 403);
        }

        if ($this->codeService->isCodeExpired($original_code)) {
            $original_code->delete();
            return response()->json(['message' => 'Code has expired'], 400);
        }

        $user->password = bcrypt($request->password);
        $user->save();

        Code::where('user_id', $user->id)->delete();

        return $this->showMessage('Operation Succeeded');
    }



}
