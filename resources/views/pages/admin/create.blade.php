@presenter(App\Application\Pages\View\AdminPagesCreatePresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    <h1>{{ $title }}</h1>

    <form action="{{ $create_url }}" method="post" name="create">
        @include('partials.input.csrf')

        @include('pages.admin.formfields', [
            'page' => $page,
            'errors' => $errors,
            'submit' => 'Create',
        ])
    </form>
@endsection
