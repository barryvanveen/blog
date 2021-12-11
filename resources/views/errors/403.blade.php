@extends('layouts.error')

@section('headHtmlMetaTags')
    @include('errors.partials.headHtmlMetaTags', ['title' => 'Forbidden'])
@endsection

@section('code', '403')
@section('message', $exception->getMessage() ?: 'Forbidden')
