@extends('layouts.cartoon')
@section('content')
<div class="cartoon-list-page cartoon-shell">
    <a href="{{ route('cartoon') }}" class="cartoon-back"><x-icon name="chevron-left" size="15"/> Back to Archive</a>
    <header class="cartoon-page-intro category-intro"><span class="cartoon-kicker"><x-icon name="layers" size="14"/> Category</span><h1>{{ $category->name }}</h1>@if($category->description)<p>{{ $category->description }}</p>@endif<div class="cartoon-count-label">{{ $cartoons->total() }} {{ Str::plural('cartoon',$cartoons->total()) }}</div></header>
    <div class="cartoon-grid-react">@forelse($cartoons as $cartoon)<x-cartoon.card :cartoon="$cartoon"/>@empty<div class="cartoon-empty cartoon-empty-large"><h2>No published cartoons here yet.</h2><a href="{{ route('cartoon') }}" class="cartoon-btn cartoon-btn-primary">Back to Archive</a></div>@endforelse</div>
    <div class="cartoon-pagination">{{ $cartoons->links() }}</div>
</div>
@endsection
