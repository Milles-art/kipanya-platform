@extends('layouts.cartoon')
@section('content')
<div class="cartoon-list-page cartoon-shell">
    <a href="{{ route('cartoon') }}" class="cartoon-back"><x-icon name="chevron-left" size="15"/> Back to Archive</a>
    <header class="cartoon-page-intro"><span class="cartoon-kicker"><x-icon name="heart" size="14"/> Your Archive</span><h1>Favorites.</h1><p>Cartoons you've chosen to keep close.</p></header>
    @if(session('status'))<div class="cartoon-notice">{{ session('status') }}</div>@endif
    @if($cartoons->count())<div class="cartoon-grid-react">@foreach($cartoons as $cartoon)<x-cartoon.card :cartoon="$cartoon"/>@endforeach</div><div class="cartoon-pagination">{{ $cartoons->links() }}</div>@else<div class="cartoon-empty cartoon-empty-large"><x-icon name="heart" size="28"/><h2>Nothing saved yet.</h2><p>Browse the archive and save artwork you want to keep.</p><a href="{{ route('cartoon') }}" class="cartoon-btn cartoon-btn-primary">Explore Cartoons</a></div>@endif
</div>
@endsection
