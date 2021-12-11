@extends('layouts.error')

@section('headHtmlMetaTags')
    @include('errors.partials.headHtmlMetaTags', ['title' => 'Server Error'])
@endsection

@section('code', '500')
@section('message', 'Server Error')
