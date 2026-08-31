@extends('layouts.app')

@section('content')
<section class="k-shell flex min-h-[75vh] items-center justify-center py-16">
    <div class="w-full max-w-md">
        <div class="k-label text-black/40">Kipanya Studio</div>
        <h1 class="mt-4 text-4xl font-semibold tracking-tight">Sign in to your workspace.</h1>
        <p class="mt-4 leading-7 text-black/55">Use the administrator phone number to receive a secure verification code.</p>

        @if (session('status'))
            <div class="mt-6 rounded-2xl bg-[#f1f1ef] p-4 text-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-6 rounded-2xl bg-red-50 p-4 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form class="mt-8 space-y-4" method="POST" action="{{ route('admin.login.request-otp') }}">
            @csrf
            <label class="block">
                <span class="k-label text-black/40">Phone number</span>
                <input name="phone" value="{{ old('phone') }}" required class="k-focus mt-2 w-full rounded-2xl border border-black/10 bg-white px-4 py-3.5" placeholder="0712 345 678">
            </label>
            <button type="submit" class="k-btn k-btn-dark w-full">Send verification code</button>
        </form>

        @if (session('admin_login_phone'))
            <form class="mt-4 space-y-4" method="POST" action="{{ route('admin.login.verify') }}">
                @csrf
                <label class="block">
                    <span class="k-label text-black/40">Verification code</span>
                    <input name="code" inputmode="numeric" maxlength="6" required class="k-focus mt-2 w-full rounded-2xl border border-black/10 bg-white px-4 py-3.5 text-center text-2xl tracking-[.35em]" placeholder="000000">
                </label>
                <button type="submit" class="k-btn k-btn-accent w-full">Enter Studio</button>
            </form>
        @endif
    </div>
</section>
@endsection
