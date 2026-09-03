@extends('layouts.platform')
@section('content')
<x-platform.home :featured="$featured" :latest="$latest" :categories="$categories" :collections="$collections" />
@endsection
