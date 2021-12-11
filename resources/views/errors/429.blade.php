@extends('layouts.error')

@section('headHtmlMetaTags')
    @include('errors.partials.headHtmlMetaTags', ['title' => 'Too Many Requests'])
@endsection

@section('code', '429')
@section('message', 'Too Many Requests')
