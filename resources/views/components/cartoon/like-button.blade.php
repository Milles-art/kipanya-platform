@props(['cartoon'])
@php($liked = auth()->check() && (bool) $cartoon->getAttribute('is_liked'))
@if(auth()->check())
<button type="button" class="kipanya-like-button {{ $liked ? 'is-liked' : '' }}" data-social-like data-url="{{ route('cartoon.like', $cartoon) }}" aria-pressed="{{ $liked ? 'true' : 'false' }}">
    <x-icon name="heart" size="19"/><span>Like</span><b data-like-count>{{ number_format($cartoon->likes_count ?? 0) }}</b>
</button>
@else
<a href="{{ route('account.login') }}" class="kipanya-like-button" data-login-like><x-icon name="heart" size="19"/><span>Like</span><b data-like-count>{{ number_format($cartoon->likes_count ?? 0) }}</b></a>
@endif
