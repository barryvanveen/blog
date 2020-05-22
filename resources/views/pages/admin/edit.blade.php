@presenter(App\Application\Pages\View\AdminPagesEditPresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    <h1>{{ $title }}</h1>

    <form action="{{ $update_url }}" method="post" name="edit">
        @include('partials.input.csrf')
        @method('PUT')

        @include('pages.admin.formfields', [
            'page' => $page,
            'errors' => $errors,
            'submit' => 'Edit',
        ])
    </form>
@endsection
