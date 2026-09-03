@extends('admin.auth-layout')
@section('content')
<section class="admin-auth-card">
    <div class="admin-auth-kicker"><span class="control-context-mark"><x-icon name="shield" size="15"/></span> Secure administrator access</div>
    <h1>Sign in to your workspace.</h1>
    <p class="admin-auth-subtitle">Use your administrator phone number to receive a one-time verification code.</p>

    @if(session('status'))<div class="admin-auth-notice"><x-icon name="check-circle" size="16"/> {{ session('status') }}</div>@endif
    @if($errors->any())<div class="admin-auth-error"><x-icon name="alert-circle" size="16"/> {{ $errors->first() }}</div>@endif
    @if(session('dev_otp_code'))<div class="admin-auth-otp"><div><span>Local development OTP</span><small>SMS gateway is in development mode.</small></div><strong>{{ session('dev_otp_code') }}</strong></div>@endif

    <form class="admin-auth-form" method="POST" action="{{ route('admin.login.request-otp') }}">
        @csrf
        <label><span>Administrator phone</span><input name="phone" value="{{ old('phone', session('admin_login_phone')) }}" required autocomplete="tel" inputmode="tel" placeholder="0712 345 678"></label>
        <button type="submit" class="k-btn k-btn-primary w-full">Send verification code <x-icon name="arrow-right" size="15"/></button>
    </form>

    @if(session('admin_login_phone'))
        <div class="admin-auth-divider"><span>Verification</span></div>
        <form class="admin-auth-form" method="POST" action="{{ route('admin.login.verify') }}">
            @csrf
            <label><span>6-digit verification code</span><input name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required class="admin-auth-code" placeholder="000000"></label>
            <button type="submit" class="k-btn k-btn-light w-full">Enter Control Center <x-icon name="arrow-right" size="15"/></button>
        </form>
    @endif
    <p class="admin-auth-footnote">Administrator access is separate from the public client account.</p>
</section>
@endsection
