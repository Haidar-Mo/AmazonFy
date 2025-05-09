<?php

namespace App\Services;

use App\Models\Code;
use Carbon\Carbon;

class CodeService{
    protected function getOrCreateVerificationCode($user_id)
    {
        $currentCode = Code::where('user_id', $user_id)
                           ->first();

        if ($currentCode && $currentCode->expired_at > now()) {
            return $currentCode->verification_code;
        }

        $verificationCode = mt_rand(100000, 999999);

        Code::create([
            'user_id' => $user_id,
            'verification_code' => $verificationCode,
            'expires_at' => Carbon::now()->addMinutes(30),
        ]);

        return $verificationCode;
    }

    public function isExpired($code)
    {
        return $code->expires_at < Carbon::now();
    }

}
