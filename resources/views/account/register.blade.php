@extends('layouts.cartoon')
@section('content')
<div class="cartoon-account-page cartoon-shell">
    <div class="cartoon-account-card">
        <div class="cartoon-account-intro">
            <span class="cartoon-kicker"><x-icon name="plus" size="14"/> Join Kipanya</span>
            <h1>Make the archive yours.</h1>
            <p>Create your Kipanya account with your phone number. No password required.</p>
        </div>
        @if(session('status'))<div class="cartoon-notice"><x-icon name="check-circle" size="15"/> {{ session('status') }}</div>@endif
        @if($errors->any())<div class="cartoon-form-errors">{{ $errors->first() }}</div>@endif
        @if(session('dev_otp_code'))<div class="cartoon-otp-notice"><strong>Local development code</strong><span>{{ session('dev_otp_code') }}</span><small>Visible only when local OTP logging is enabled.</small></div>@endif
        <div class="cartoon-account-form-card">
            <form action="{{ route('account.register.request-otp') }}" method="POST" class="cartoon-account-form">
                @csrf
                <label>Phone number<input name="phone" value="{{ old('phone', session('user_registration_phone')) }}" required placeholder="0712 345 678" autocomplete="tel"></label>
                <button class="cartoon-btn cartoon-btn-primary" type="submit">Send verification code <x-icon name="arrow-right" size="15"/></button>
            </form>
            @if(session('user_registration_phone'))
                <div class="cartoon-form-divider"></div>
                <form action="{{ route('account.register.verify') }}" method="POST" class="cartoon-account-form">
                    @csrf
                    <label>Your name<input name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Your name"></label>
                    <label>Verification code<input name="code" inputmode="numeric" autocomplete="one-time-code" maxlength="6" required placeholder="6-digit code"></label>
                    <button class="cartoon-btn cartoon-btn-outline" type="submit">Create my account <x-icon name="arrow-right" size="15"/></button>
                </form>
            @endif
        </div>
        <p class="cartoon-account-switch">Already have an account? <a href="{{ route('account.login') }}">Sign in</a></p>
    </div>
</div>
@endsection
