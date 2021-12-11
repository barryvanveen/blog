@extends('layouts.error')

@section('headHtmlMetaTags')
    @include('errors.partials.headHtmlMetaTags', ['title' => 'Service Unavailable'])
@endsection

@section('code', '503')
@section('message', $exception->getMessage() ?: 'Service Unavailable')
