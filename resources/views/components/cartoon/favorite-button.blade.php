@props(['cartoon', 'size' => 'md'])
@php
    $saved = false;
    if (auth()->check()) {
        auth()->user()->loadMissing('favorites:id');
        $saved = auth()->user()->favorites->contains('id', $cartoon->id);
    }
@endphp
@if(auth()->check())
<form method="POST" action="{{ route('favorites.toggle', $cartoon) }}" class="inline" onclick="event.stopPropagation()">
    @csrf
    <button type="submit" class="cartoon-favorite {{ $saved ? 'is-saved' : '' }} {{ $size === 'sm' ? 'small' : '' }}" aria-label="{{ $saved ? 'Remove from favorites' : 'Add to favorites' }}" title="{{ $saved ? 'Remove from favorites' : 'Add to favorites' }}">
        <x-icon name="heart" size="{{ $size === 'sm' ? 16 : 18 }}"/>
    </button>
</form>
@else
<a href="{{ route('account.login') }}" class="cartoon-favorite {{ $size === 'sm' ? 'small' : '' }}" onclick="event.stopPropagation()" aria-label="Sign in to save" title="Sign in to save"><x-icon name="heart" size="{{ $size === 'sm' ? 16 : 18 }}"/></a>
@endif
