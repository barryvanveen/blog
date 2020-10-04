@presenter(App\Application\Comments\View\AdminCommentsEditPresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    <h1>{{ $title }}</h1>

    <form action="{{ $update_url }}" method="post" name="edit">
        @include('partials.input.csrf')
        @method('PUT')

        @include('comments.admin.formfields', [
            'comment' => $comment,
            'articles' => $articles,
            'statuses' => $statuses,
            'errors' => $errors,
            'submit' => 'Edit',
        ])
    </form>
@endsection
