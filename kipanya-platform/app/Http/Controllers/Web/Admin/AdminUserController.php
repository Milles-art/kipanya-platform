<?php

namespace App\Http\Controllers\Web\Admin;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

final class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($w) => $w->where('name', 'like', '%'.$request->q.'%')->orWhere('email', 'like', '%'.$request->q.'%')->orWhere('phone', 'like', '%'.$request->q.'%')))
            ->when($request->filled('role'), fn ($q) => $q->where('role', $request->role))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()->paginate(12)->withQueryString();

        return view('admin/users/index', [
            'users' => $users,
            'roles' => UserRole::cases(),
            'statuses' => UserStatus::cases(),
            'counts' => ['all' => User::count(), 'admins' => User::where('role', UserRole::Admin)->count(), 'active' => User::where('status', UserStatus::Active)->count(), 'suspended' => User::where('status', UserStatus::Suspended)->count()],
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::enum(UserRole::class)],
            'status' => ['required', Rule::enum(UserStatus::class)],
        ]);
        if ($user->is(auth()->user()) && $data['status'] !== UserStatus::Active->value) {
            return back()->with('error', 'Your own administrator account cannot be suspended here.');
        }
        $before = [$user->role?->value, $user->status];
        $user->update($data);
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'Updated user', 'description' => "Updated {$user->name}'s role/status.", 'application' => 'Platform', 'subject_type' => User::class, 'subject_id' => $user->id, 'ip_address' => $request->ip()]);
        return back()->with('success', "{$user->name}'s access was updated.");
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $request->validate(['password' => ['required', 'string', 'min:8', 'confirmed']]);
        $user->update(['password' => Hash::make($request->password)]);
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'Reset password', 'description' => "Reset the password for {$user->name}.", 'application' => 'Platform', 'subject_type' => User::class, 'subject_id' => $user->id, 'ip_address' => $request->ip()]);
        return back()->with('success', 'Password reset successfully.');
    }
}
