@extends('admin.control-layout')
@section('content')
<div class="control-page-head k-reveal"><div><div class="k-label k-muted">Account</div><h1>Administrator Profile</h1><p>Manage your Control Center identity and security.</p></div></div>
@if(session('success'))<div class="control-alert success">{{ session('success') }}</div>@endif @if(session('error'))<div class="control-alert error">{{ session('error') }}</div>@endif
<div class="control-profile-grid">
<section class="control-panel control-form-panel"><div class="control-panel-head"><h2>Profile details</h2></div><form method="POST" action="{{ route('admin.profile.update') }}" class="control-admin-form">@csrf @method('PATCH')<label>Full name<input name="name" value="{{ old('name',$admin->name) }}" required></label><label>Email address<input type="email" name="email" value="{{ old('email',$admin->email) }}" required></label><label>Phone number<input value="{{ $admin->phone ?: 'Not provided' }}" disabled></label><button class="k-btn k-btn-primary" type="submit">Save profile</button></form></section>
<section class="control-panel control-form-panel"><div class="control-panel-head"><h2>Security</h2></div><form method="POST" action="{{ route('admin.profile.password') }}" class="control-admin-form">@csrf @method('PATCH')<label>Current password<input type="password" name="current_password" required></label><label>New password<input type="password" name="password" minlength="8" required></label><label>Confirm new password<input type="password" name="password_confirmation" minlength="8" required></label><button class="k-btn k-btn-light" type="submit">Change password</button></form></section>
</div>
@endsection
