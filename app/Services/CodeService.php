<?php

namespace App\Services;

use App\Models\Code;
use App\Models\User;
use App\Notifications\PhoneNumberVerificationCodeNotification;
use App\Notifications\VerificationCodeNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;

class CodeService
{
    public function getOrCreateVerificationCode($user_id)
    {
        $currentCode = Code::where('user_id', $user_id)
            ->first();

        if ($currentCode && $currentCode->created_at > now()->subMinutes(60)) {
            return $currentCode;
        }

        $verificationCode = mt_rand(100000, 999999);
        $code = Code::create([
            'user_id' => $user_id,
            'verification_code' => $verificationCode,
            'expired_at' => Carbon::now()->addMinutes(30),
        ]);

        return $code;
    }

    public function isCodeExpired($code)
    {
        return $code->expired_at < Carbon::now();
    }

    public function verifyCode($request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $request_code = $request->verification_code;
            $original_code = $this->getOrCreateVerificationCode($request->route('id'));

            if (!($request_code && ($request_code == $original_code->verification_code))) {
                return response()->json(['message' => 'Invalid verification Code'], 403);
            }

            $user = User::findOrFail($request->route('id'));

            if ($user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Email already verified'], 400);
            }

            if ($this->isCodeExpired($original_code)) {
                $original_code->delete();
                return response()->json(['message' => 'Code has expired'], 400);
            }
            $user->email_verified_at = Carbon::now();
            $user->save();
            $original_code->delete();
            return response()->json(['message' => 'Code has been confirmed']);
        });
    }

    public function resendCode($request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user = User::findOrFail($request->route('id'));
            $user->verificationCode?->delete();
            $code = $this->getOrCreateVerificationCode($user->id);

            DB::afterCommit(function () use ($user, $code) {
                Notification::send($user, new VerificationCodeNotification($user, $code->verification_code));
            });

            return response()->json(['message' => 'Verification Code sent, Check your email.']);
        });

    }

    public function verifyPhoneNumberCode($request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $request_code = $request->verification_code;
            $original_code = $this->getOrCreateVerificationCode($request->route('id'));

            if (!($request_code && ($request_code == $original_code->verification_code))) {
                return response()->json(['message' => 'Invalid verification Code'], 403);
            }

            $user = User::findOrFail($request->route('id'));

            if (!is_null($user->email_verified_at)) {
                return response()->json(['message' => 'Phone already verified'], 400);
            }

            if ($this->isCodeExpired($original_code)) {
                $original_code->delete();
                return response()->json(['message' => 'Code has expired'], 400);
            }
            $user->email_verified_at = Carbon::now();
            $user->save();
            $original_code->delete();
            return response()->json(['message' => 'Code has been confirmed']);
        });
    }

    public function resendPhoneNumberCode($request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $user = User::findOrFail($request->route('id'));
            $user->verificationCode?->delete();
            $code = $this->getOrCreateVerificationCode($user->id);

            $usersWithRoles = User::role(['admin', 'supervisor'], 'api')->get();
            Notification::send($usersWithRoles, new PhoneNumberVerificationCodeNotification($user, $code->verification_code));

            return response()->json(['message' => 'Verification Code sent.']);
        });

    }

}
