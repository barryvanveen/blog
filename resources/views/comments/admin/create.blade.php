@presenter(App\Application\Comments\View\AdminCommentsCreatePresenter)
@extends('layouts.admin')

@section('title', $title)

@section('body')
    @include('partials.admin.create_or_update', [
        'title' => $title,
        'url' => $url,
        'method' => 'POST',
        'form_name' => 'create',
        'formfields_template' => 'comments.admin.formfields',
        'formfields_data' => [
            'articles' => $articles,
            'statuses' => $statuses,
            'comment' => $comment,
            'errors' => $errors,
        ],
        'submit' => 'Create',
    ])
@endsection
