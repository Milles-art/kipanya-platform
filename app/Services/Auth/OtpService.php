<?php

namespace App\Services\Auth;

use App\Enums\OtpPurpose;
use App\Integrations\Sms\SmsGateway;
use App\Models\OtpCode;
use App\Support\PhoneNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class OtpService
{
    public const TTL_MINUTES = 5;
    public const MAX_ATTEMPTS = 5;
    public const RESEND_COOLDOWN_SECONDS = 60;

    public function __construct(private readonly SmsGateway $sms) {}

    public function send(string $phone, OtpPurpose $purpose): OtpCode
    {
        $normalized = PhoneNumber::normalize($phone)->value();

        $latest = OtpCode::query()
            ->where('phone', $normalized)
            ->where('purpose', $purpose->value)
            ->latest('id')
            ->first();

        if ($latest?->last_sent_at?->gt(now()->subSeconds(self::RESEND_COOLDOWN_SECONDS))) {
            throw ValidationException::withMessages([
                'phone' => ['Please wait before requesting another code.'],
            ]);
        }

        $plainCode = (string) random_int(100000, 999999);

        $otp = DB::transaction(function () use ($normalized, $purpose, $plainCode) {
            OtpCode::query()
                ->where('phone', $normalized)
                ->where('purpose', $purpose->value)
                ->whereNull('verified_at')
                ->update(['verified_at' => now()]);

            return OtpCode::create([
                'phone' => $normalized,
                'code_hash' => Hash::make($plainCode),
                'purpose' => $purpose->value,
                'attempts' => 0,
                'expires_at' => now()->addMinutes(self::TTL_MINUTES),
                'last_sent_at' => now(),
            ]);
        });

        $this->sms->send(
            $normalized,
            "Your Kipanya verification code is {$plainCode}. It expires in " . self::TTL_MINUTES . " minutes."
        );

        return $otp;
    }

    public function verify(string $phone, OtpPurpose $purpose, string $code): OtpCode
    {
        $normalized = PhoneNumber::normalize($phone)->value();

        $result = DB::transaction(function () use ($normalized, $purpose, $code): array {
            $otp = OtpCode::query()
                ->where('phone', $normalized)
                ->where('purpose', $purpose->value)
                ->whereNull('verified_at')
                ->latest('id')
                ->lockForUpdate()
                ->first();

            if (!$otp || $otp->isExpired() || $otp->hasExceededAttempts()) {
                return ['otp' => null, 'error' => true];
            }

            if (!Hash::check($code, $otp->code_hash)) {
                $otp->increment('attempts');

                return ['otp' => null, 'error' => true];
            }

            $otp->forceFill(['verified_at' => now()])->save();

            return ['otp' => $otp->fresh(), 'error' => false];
        });

        if ($result['error']) {
            throw ValidationException::withMessages([
                'code' => ['The verification code is invalid or expired.'],
            ]);
        }

        /** @var OtpCode $otp */
        return $result['otp'];
    }
}
