<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\OtpPurpose;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\OtpService;
use App\Support\PhoneNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

final class AdminAuthController extends Controller
{
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    public function requestOtp(
        Request $request,
        OtpService $otpService
    ): RedirectResponse {
        $validated = $request->validate([
            'phone' => ['required', 'string'],
        ]);

        $phone = PhoneNumber::normalize($validated['phone'])->value();

        $user = User::query()
            ->where('phone', $phone)
            ->where('status', UserStatus::Active->value)
            ->first();

        $request->session()->forget('dev_otp_code');

        if ($user?->isAdmin()) {
            $otpService->send($phone, OtpPurpose::Login);

            // In local/testing environments the generated code is deliberately
            // available to the browser so development does not depend on SMS.
            if ($otpService->lastPlainCode()) {
                $request->session()->put('dev_otp_code', $otpService->lastPlainCode());
            }

            $request->session()->put('admin_login_phone', $phone);
            $request->session()->save();

            return back()->with('status', 'Verification code generated.');
        }

        $request->session()->forget('admin_login_phone');
        $request->session()->save();

        $message = app()->environment(['local', 'testing'])
            ? 'No active administrator account was found for that phone number.'
            : 'If the account is eligible, a verification code has been sent.';

        return back()->withErrors(['phone' => $message]);
    }

    public function login(
        Request $request,
        OtpService $otpService
    ): RedirectResponse {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $phone = $request->session()->get('admin_login_phone');

        if (! $phone) {
            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'code' => 'Your login session expired. Please request a new verification code.',
                ]);
        }

        $otpService->verify(
            $phone,
            OtpPurpose::Login,
            $validated['code']
        );

        $user = User::query()
            ->where('phone', $phone)
            ->where('status', UserStatus::Active->value)
            ->firstOrFail();

        abort_unless(
            $user->isAdmin(),
            403,
            'Administrator access required.'
        );

        Auth::guard('web')->login($user);

        $request->session()->regenerate();
        $request->session()->forget(['admin_login_phone', 'dev_otp_code']);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}