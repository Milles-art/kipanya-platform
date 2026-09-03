@extends('admin.layout')
@section('content')
<div class="studio-page-head"><div><div class="k-label k-muted">{{ $cartoon->title }} / Episode {{ $episode->episode_number }}</div><h1 class="studio-title">Edit episode.</h1><p class="studio-subtitle">Update metadata, video source and publishing state.</p></div><a href="{{ route('admin.content.show',$cartoon) }}" class="k-btn k-btn-light">Back to cartoon</a></div>
@if($errors->any())<div class="studio-form-errors mt-5"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@include('admin.content.episodes.form', ['action'=>route('admin.episodes.update',[$cartoon,$episode]), 'method'=>'PUT', 'episode'=>$episode, 'next'=>$episode->episode_number])
<form id="delete-episode" onsubmit="return confirm('Delete this episode permanently?')" method="POST" action="{{ route('admin.episodes.destroy',[$cartoon,$episode]) }}" class="mt-3 flex justify-end">@csrf @method('DELETE')<button class="k-btn k-btn-light text-red-600" type="submit">Delete episode</button></form>
@endsection
