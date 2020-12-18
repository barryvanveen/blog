@presenter(App\Application\Articles\View\AdminArticlesEditPresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    @include('partials.admin.create_or_update', [
        'title' => $title,
        'url' => $url,
        'method' => 'PUT',
        'form_name' => 'edit',
        'formfields_template' => 'articles.admin.formfields',
        'formfields_data' => [
            'article' => $article,
            'statuses' => $statuses,
            'errors' => $errors,
        ],
        'submit' => 'Edit',
    ])
@endsection
