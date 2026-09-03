@extends('admin.layout')
@section('content')
<div class="studio-page-head"><div><div class="k-label k-muted">{{ $cartoon->title }} / Episodes</div><h1 class="studio-title">Add episode.</h1><p class="studio-subtitle">Keep episodes attached to the cartoon they belong to.</p></div><a href="{{ route('admin.content.show',$cartoon) }}" class="k-btn k-btn-light">Back to cartoon</a></div>
@if($errors->any())<div class="studio-form-errors mt-5"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@include('admin.content.episodes.form', ['action'=>route('admin.episodes.store',$cartoon), 'method'=>'POST', 'episode'=>null, 'next'=>$next])
@endsection
