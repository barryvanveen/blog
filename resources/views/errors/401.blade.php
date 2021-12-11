@extends('layouts.error')

@section('headHtmlMetaTags')
    @include('errors.partials.headHtmlMetaTags', ['title' => 'Unauthorized'])
@endsection

@section('code', '401')
@section('message', 'Unauthorized')
