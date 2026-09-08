@extends('layouts.wear')
@section('content')
<div class="store-page simple-page"><span class="eyebrow">{{ $eyebrow ?? 'My Kipanya' }}</span><h1>{{ $title }}</h1><p>{{ $copy }}</p><div class="empty-card"><div><x-icon name="{{ str_contains(strtolower($title),'order')?'package':(str_contains(strtolower($title),'address')?'location':(str_contains(strtolower($title),'coupon')?'tag':'settings')) }}" size="26"/></div><h2>{{ $card ?? $title }}</h2><span>{{ $copy }}</span><a class="primary-button" href="{{ route('wear.catalog') }}">Explore shopping <x-icon name="arrow-right" size="15"/></a></div></div>
@endsection
