@presenter(App\Application\Comments\View\AdminCommentsEditPresenter)
@extends('layouts.admin')

@section('title', $title)

@section('body')
    @include('partials.admin.create_or_update', [
        'title' => $title,
        'url' => $url,
        'method' => 'PUT',
        'form_name' => 'edit',
        'formfields_template' => 'comments.admin.formfields',
        'formfields_data' => [
            'articles' => $articles,
            'statuses' => $statuses,
            'comment' => $comment,
            'errors' => $errors,
        ],
        'submit' => 'Edit',
    ])
@endsection
