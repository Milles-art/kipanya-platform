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

final class UserAccountController extends Controller
{
    public function showLogin(): View { return view('account.login'); }

    public function requestOtp(Request $request, OtpService $otpService): RedirectResponse
    {
        $data = $request->validate(['phone' => ['required','string']]);
        $phone = PhoneNumber::normalize($data['phone'])->value();
        $user = User::query()->where('phone', $phone)->where('status', UserStatus::Active->value)->first();
        if ($user && !$user->isAdmin()) { $otpService->send($phone, OtpPurpose::Login); }
        $request->session()->put('user_login_phone', $phone);
        return back()->with('status', 'If the account exists, a verification code has been sent.');
    }

    public function login(Request $request, OtpService $otpService): RedirectResponse
    {
        $data = $request->validate(['code' => ['required','digits:6']]);
        $phone = $request->session()->get('user_login_phone');
        abort_unless($phone, 422, 'Login session expired.');
        $otpService->verify($phone, OtpPurpose::Login, $data['code']);
        $user = User::query()->where('phone', $phone)->where('status', UserStatus::Active->value)->firstOrFail();
        abort_unless(!$user->isAdmin(), 403, 'Use the Studio login for administrator access.');
        Auth::guard('web')->login($user);
        $request->session()->regenerate();
        $request->session()->forget('user_login_phone');
        return redirect()->intended(route('account'));
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $favorites = $user->favorites()->with('category')->where('status', ContentStatus::Published->value)->latest('favorites.created_at')->take(6)->get();
        $progress = $user->watchProgress()->with(['cartoon.category', 'episode'])->latest('last_watched_at')->take(6)->get();
        return view('account.index', compact('user', 'favorites', 'progress'));
    }

    public function favorites(Request $request): View
    {
        $cartoons = $request->user()->favorites()->with('category')->where('status', ContentStatus::Published->value)->latest('favorites.created_at')->paginate(18);
        return view('account.favorites', compact('cartoons'));
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

    public function markWatched(Request $request, Cartoon $cartoon): RedirectResponse
    {
        $data = $request->validate(['episode_id' => ['required','integer'], 'progress_seconds' => ['nullable','integer','min:0','max:86400'], 'completed' => ['nullable','boolean']]);
        $episode = $cartoon->episodes()->whereKey($data['episode_id'])->where('status', ContentStatus::Published->value)->firstOrFail();
        $request->user()->watchProgress()->updateOrCreate(
            ['episode_id' => $episode->id],
            ['cartoon_id' => $cartoon->id, 'progress_seconds' => $data['progress_seconds'] ?? 0, 'completed_at' => !empty($data['completed']) ? now() : null, 'last_watched_at' => now()]
        );
        return back()->with('status', 'Watch progress saved.');
    }
}
