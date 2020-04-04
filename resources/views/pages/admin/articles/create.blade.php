@presenter(App\Application\Articles\View\AdminArticlesCreatePresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    <h1>{{ $title }}</h1>

    <form action="{{ $create_article_url }}" method="post" name="create">
        @csrf

        @include('pages.admin.articles.formfields', [
            'article' => null,
            'statuses' => $statuses,
            'errors' => $errors,
            'submit' => 'Create',
        ])
    </form>
@endsection
