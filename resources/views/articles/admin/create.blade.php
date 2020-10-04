@presenter(App\Application\Articles\View\AdminArticlesCreatePresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    <h1>{{ $title }}</h1>

    <form action="{{ $store_url }}" method="post" name="create">
        @include('partials.input.csrf')

        @include('articles.admin.formfields', [
            'article' => $article,
            'statuses' => $statuses,
            'errors' => $errors,
            'submit' => 'Create',
        ])
    </form>
@endsection
