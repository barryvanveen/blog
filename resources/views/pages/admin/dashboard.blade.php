@presenter(App\Application\View\Admin\DashboardPresenter)

@extends('layouts.base')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('body')
    <h1>Hi {{ $name }}</h1>

    <form action="{{ $form_url }}" method="post" name="logout">
        @include('pages.partials.input.csrf')

        @include('pages.partials.input.button', ['type' => 'submit', 'name' => 'submit', 'title' => 'Logout'])
    </form>
@endsection
