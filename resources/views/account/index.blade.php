@extends('layouts.cartoon')
@section('content')
<div class="cartoon-account-page cartoon-shell">
    <div class="cartoon-account-header">
        <div><span class="cartoon-kicker"><x-icon name="user" size="14"/> Your Kipanya</span><h1>Welcome, {{ $user->name }}.</h1><p>Your Cartoon Archive, favorites and profile in one place.</p></div>
        <form action="{{ route('account.logout') }}" method="POST">@csrf<button class="cartoon-btn cartoon-btn-outline" type="submit"><x-icon name="logout" size="15"/> Sign out</button></form>
    </div>
    @if(session('status'))<div class="cartoon-notice"><x-icon name="check-circle" size="15"/> {{ session('status') }}</div>@endif
    <div class="cartoon-account-grid">
        <section class="cartoon-account-section">
            <div class="cartoon-section-head-react"><div><div class="cartoon-section-label">Favorites</div><h2>Saved for later</h2></div><a href="{{ route('favorites') }}">View all <x-icon name="arrow-right" size="15"/></a></div>
            <div class="cartoon-grid-react">
                @forelse($favorites as $cartoon)<x-cartoon.card :cartoon="$cartoon"/>@empty<div class="cartoon-empty cartoon-empty-large"><x-icon name="heart" size="25"/><h2>Nothing saved yet.</h2><p>Explore the Cartoon Archive and save artwork you want to keep.</p><a href="{{ route('cartoon') }}" class="cartoon-btn cartoon-btn-primary">Explore Cartoons</a></div>@endforelse
            </div>
        </section>
        <aside class="cartoon-account-profile">
            <div class="cartoon-section-label">Profile</div>
            <h2>Your details</h2>
            <form action="{{ route('account.profile.update') }}" method="POST" class="cartoon-account-form">
                @csrf @method('PATCH')
                <label>Name<input name="name" value="{{ old('name',$user->name) }}" required></label>
                <label>Email<input name="email" type="email" value="{{ old('email',$user->email) }}"></label>
                <label>Phone<input value="{{ $user->phone }}" disabled></label>
                <button class="cartoon-btn cartoon-btn-primary" type="submit">Save profile <x-icon name="check-circle" size="15"/></button>
            </form>
            <a href="{{ route('cartoon') }}" class="cartoon-btn cartoon-btn-outline cartoon-account-explore">Explore Cartoon Archive <x-icon name="arrow-right" size="15"/></a>
        </aside>
    </div>
</div>
@endsection
