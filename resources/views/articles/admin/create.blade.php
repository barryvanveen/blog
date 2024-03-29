@presenter(App\Application\Articles\View\AdminArticlesCreatePresenter)
@extends('layouts.admin')

@section('title', $title)

@section('body')
    @include('partials.admin.create_or_update', [
        'title' => $title,
        'url' => $url,
        'method' => 'POST',
        'form_name' => 'create',
        'formfields_template' => 'articles.admin.formfields',
        'formfields_data' => [
            'article' => $article,
            'statuses' => $statuses,
            'errors' => $errors,
        ],
        'submit' => 'Create',
    ])
@endsection
