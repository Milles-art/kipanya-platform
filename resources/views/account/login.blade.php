@extends('layouts.cartoon')
@section('content')
<div class="cartoon-account-page cartoon-shell">
    <div class="cartoon-account-card">
        <div class="cartoon-account-intro">
            <span class="cartoon-kicker"><x-icon name="user" size="14"/> Kipanya account</span>
            <h1>Pick up where you left off.</h1>
            <p>Sign in with your phone number to keep your Cartoon Archive favorites and Wear designs synced.</p>
        </div>
        @if(session('status'))<div class="cartoon-notice"><x-icon name="check-circle" size="15"/> {{ session('status') }}</div>@endif
        @if($errors->any())<div class="cartoon-form-errors">{{ $errors->first() }}</div>@endif
        @if(session('dev_otp_code'))<div class="cartoon-otp-notice"><strong>Local development code</strong><span>{{ session('dev_otp_code') }}</span><small>Visible only when local OTP logging is enabled.</small></div>@endif
        <div class="cartoon-account-form-card">
            <form action="{{ route('account.login.request-otp') }}" method="POST" class="cartoon-account-form">
                @csrf
                <label>Phone number<input name="phone" value="{{ old('phone') }}" required placeholder="0712 345 678" autocomplete="tel"></label>
                <button class="cartoon-btn cartoon-btn-primary" type="submit">Send verification code <x-icon name="arrow-right" size="15"/></button>
            </form>
            @if(session('user_login_phone'))
                <div class="cartoon-form-divider"></div>
                <form action="{{ route('account.login.verify') }}" method="POST" class="cartoon-account-form">
                    @csrf
                    <input type="hidden" name="phone" value="{{ session('user_login_phone') }}">
                    <label>Verification code<input name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required placeholder="6-digit code"></label>
                    <button class="cartoon-btn cartoon-btn-outline" type="submit">Continue to account <x-icon name="arrow-right" size="15"/></button>
                </form>
            @endif
        </div>
        <p class="cartoon-account-switch">New to Kipanya? <a href="{{ route('account.register') }}">Create an account</a></p>
    </div>
</div>
@endsection
