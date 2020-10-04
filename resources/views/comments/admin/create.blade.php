@presenter(App\Application\Comments\View\AdminCommentsCreatePresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    <h1>{{ $title }}</h1>

    <form action="{{ $store_url }}" method="post" name="create">
        @include('partials.input.csrf')

        @include('comments.admin.formfields', [
            'articles' => $articles,
            'statuses' => $statuses,
            'comment' => $comment,
            'errors' => $errors,
            'submit' => 'Create',
        ])
    </form>
@endsection
