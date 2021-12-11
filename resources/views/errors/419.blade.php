@extends('layouts.error')

@section('headHtmlMetaTags')
    @include('errors.partials.headHtmlMetaTags', ['title' => 'Page Expired'])
@endsection

@section('code', '419')
@section('message', 'Page Expired')
