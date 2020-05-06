@presenter(App\Application\Articles\View\AdminArticlesEditPresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    <h1>{{ $title }}</h1>

    <form action="{{ $update_article_url }}" method="post" name="edit">
        @include('partials.input.csrf')
        @method('PUT')

        @include('articles.admin.formfields', [
            'article' => $article,
            'statuses' => $statuses,
            'errors' => $errors,
            'submit' => 'Edit',
        ])
    </form>
@endsection
