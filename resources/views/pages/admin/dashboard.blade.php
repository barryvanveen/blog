@presenter(App\Application\View\Admin\DashboardPresenter)

@extends('layouts.base')

@section('title', 'Dashboard')

@section('body')
    <h1>Hi {{ $name }}</h1>

    <form action="{{ $form_url }}" method="post" name="logout">
        @include('pages.partials.input.csrf', ['token' => $token])

        @include('pages.partials.input.button', ['type' => 'submit', 'name' => 'submit', 'title' => 'Logout'])
    </form>
@endsection
