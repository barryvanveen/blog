@extends('layouts.error')

@section('headHtmlMetaTags')
    @include('errors.partials.headHtmlMetaTags', ['title' => 'Not Found'])
@endsection

@section('code', '404')
@section('message', 'Not Found')
