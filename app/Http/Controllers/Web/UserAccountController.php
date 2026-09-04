<?php

namespace App\Http\Controllers\Web;

use App\Enums\ContentStatus;
use App\Enums\OtpPurpose;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\Cartoon;
use App\Models\User;
use App\Services\Auth\OtpService;
use App\Support\PhoneNumber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

final class UserAccountController extends Controller
{
    public function showLogin(): View
    {
        return view('account.login');
    }

    public function showRegister(): View
    {
        return view('account.register');
    }

    public function requestRegistrationOtp(Request $request, OtpService $otpService): RedirectResponse
    {
        $data = $request->validate(['phone' => ['required', 'string', 'max:30']]);
        $phone = PhoneNumber::normalize($data['phone'])->value();
        $key = 'web-register-otp:' . sha1($phone);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'phone' => ['Too many requests. Please try again later.'],
            ]);
        }

        RateLimiter::hit($key, 3600);

        if (User::query()->where('phone', $phone)->exists()) {
            $request->session()->forget(['user_registration_phone', 'dev_otp_code']);
            return back()->withInput()->with('status', 'If this number is not already registered, a verification code has been sent.');
        }

        $otpService->send($phone, OtpPurpose::Registration);
        $request->session()->put('user_registration_phone', $phone);
        if ($otpService->lastPlainCode()) {
            $request->session()->put('dev_otp_code', $otpService->lastPlainCode());
        }

        return back()->with('status', 'A verification code has been sent to your phone.');
    }

    public function register(Request $request, OtpService $otpService): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:80'],
            'code' => ['required', 'digits:6'],
        ]);
        $phone = $request->session()->get('user_registration_phone');
        abort_unless($phone, 422, 'Registration session expired.');

        $user = DB::transaction(function () use ($data, $phone, $otpService): User {
            $otpService->verify($phone, OtpPurpose::Registration, $data['code']);

            if (User::query()->where('phone', $phone)->lockForUpdate()->exists()) {
                throw ValidationException::withMessages([
                    'phone' => ['This phone number is already registered. Please sign in instead.'],
                ]);
            }

            return User::query()->create([
                'name' => trim($data['name']),
                'phone' => $phone,
                'phone_verified_at' => now(),
                'status' => UserStatus::Active->value,
            ]);
        });

        Auth::guard('web')->login($user);
        $request->session()->regenerate();
        $request->session()->forget(['user_registration_phone', 'dev_otp_code']);

        return redirect()->intended(route('account'))->with('status', 'Welcome to Kipanya. Your account is ready.');
    }

    public function requestOtp(Request $request, OtpService $otpService): RedirectResponse
    {
        $data = $request->validate(['phone' => ['required', 'string', 'max:30']]);
        $phone = PhoneNumber::normalize($data['phone'])->value();
        $key = 'web-login-otp:' . sha1($phone);

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'phone' => ['Too many requests. Please try again later.'],
            ]);
        }

        RateLimiter::hit($key, 3600);

        $user = User::query()->where('phone', $phone)->where('status', UserStatus::Active->value)->first();
        if ($user && !$user->isAdmin()) {
            $otpService->send($phone, OtpPurpose::Login);
            $request->session()->put('user_login_phone', $phone);
            if ($otpService->lastPlainCode()) {
                $request->session()->put('dev_otp_code', $otpService->lastPlainCode());
            }
        }

        return back()->withInput()->with('status', 'If the account exists, a verification code has been sent.');
    }

    public function login(Request $request, OtpService $otpService): RedirectResponse
    {
        $data = $request->validate(['code' => ['required', 'digits:6']]);
        $phone = $request->session()->get('user_login_phone');
        abort_unless($phone, 422, 'Login session expired.');

        $otpService->verify($phone, OtpPurpose::Login, $data['code']);
        $user = User::query()->where('phone', $phone)->where('status', UserStatus::Active->value)->first();

        if (!$user || $user->isAdmin()) {
            throw ValidationException::withMessages([
                'code' => ['Unable to authenticate this account.'],
            ]);
        }

        Auth::guard('web')->login($user);
        $request->session()->regenerate();
        $request->session()->forget(['user_login_phone', 'dev_otp_code']);

        return redirect()->intended(route('account'));
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $favorites = $user->favorites()->with('category')->withExists(['favorites as is_favorited' => fn ($query) => $query->whereKey($user->id)])->where('status', ContentStatus::Published->value)->latest('favorites.created_at')->take(6)->get();
        return view('account.index', compact('user', 'favorites'));
    }

    public function favorites(Request $request): View
    {
        $cartoons = $request->user()->favorites()->with('category')->withExists(['favorites as is_favorited' => fn ($query) => $query->whereKey($request->user()->id)])->where('status', ContentStatus::Published->value)->latest('favorites.created_at')->paginate(18);
        return view('pages.public.favorites', compact('cartoons'));
    }

    public function toggleFavorite(Request $request, Cartoon $cartoon): RedirectResponse
    {
        abort_unless($cartoon->status === ContentStatus::Published, 404);
        $favorite = $request->user()->favorites()->whereKey($cartoon->id)->exists();
        if ($favorite) {
            $request->user()->favorites()->detach($cartoon->id);
        } else {
            $request->user()->favorites()->syncWithoutDetaching([$cartoon->id]);
        }
        return back()->with('status', $favorite ? 'Removed from favorites.' : 'Added to favorites.');
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $data = $request->validate(['name' => ['required','string','max:120'], 'email' => ['nullable','email','max:180']]);
        $request->user()->update($data);
        return back()->with('status', 'Profile updated.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

}
