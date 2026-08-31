@extends('layouts.app')
@section('content')
<section class="k-shell py-12 sm:py-16">
    <a href="{{ route('discover') }}" class="text-sm text-black/45 hover:text-black">Back to library</a>
    <div class="mt-10 max-w-3xl"><div class="k-label text-black/40">Collection</div><h1 class="k-display mt-4 text-5xl font-semibold sm:text-7xl">{{ $collection->name }}</h1>@if($collection->description)<p class="mt-6 text-lg leading-8 text-black/55">{{ $collection->description }}</p>@endif</div>
    @if($collection->cartoons->count())
        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($collection->cartoons as $cartoon)
                <a href="{{ route('watch', $cartoon) }}" class="k-card group"><div class="aspect-[16/10] overflow-hidden bg-[#ecece9]">@if($cartoon->thumbnail_url)<img src="{{ $cartoon->thumbnail_url }}" alt="{{ $cartoon->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">@endif</div><div class="p-5"><div class="k-label text-black/35">{{ $cartoon->category->name }}</div><h2 class="mt-2 text-xl font-semibold">{{ $cartoon->title }}</h2></div></a>
            @endforeach
        </div>
    @else
        <div class="mt-10 rounded-3xl bg-[#f7f7f5] px-6 py-16 text-center text-sm text-black/50">This collection is being prepared.</div>
    @endif
</section>
@endsection
