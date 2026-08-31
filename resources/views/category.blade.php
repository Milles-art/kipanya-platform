@extends('layouts.app')
@section('content')
<section class="k-shell py-12 sm:py-16">
    <a href="{{ route('discover') }}" class="text-sm text-black/45 hover:text-black">Back to library</a>
    <div class="mt-10 max-w-3xl"><div class="k-label text-black/40">Category</div><h1 class="k-display mt-4 text-5xl font-semibold sm:text-7xl">{{ $category->name }}</h1>@if($category->description)<p class="mt-6 text-lg leading-8 text-black/55">{{ $category->description }}</p>@endif</div>
    <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($cartoons as $cartoon)
            <a href="{{ route('watch', $cartoon) }}" class="k-card group"><div class="aspect-[16/10] overflow-hidden bg-[#ecece9]">@if($cartoon->thumbnail_url)<img src="{{ $cartoon->thumbnail_url }}" alt="{{ $cartoon->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]">@endif</div><div class="p-5"><div class="k-label text-black/35">{{ $category->name }}</div><h2 class="mt-2 text-xl font-semibold">{{ $cartoon->title }}</h2><p class="mt-2 line-clamp-2 text-sm leading-6 text-black/50">{{ $cartoon->description }}</p></div></a>
        @endforeach
    </div>
    <div class="mt-10">{{ $cartoons->links() }}</div>
</section>
@endsection
