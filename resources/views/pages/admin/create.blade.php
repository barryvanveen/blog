@presenter(App\Application\Pages\View\AdminPagesCreatePresenter)
@extends('layouts.admin')

@section('title', $title)

@section('body')
    @include('partials.admin.create_or_update', [
        'title' => $title,
        'url' => $url,
        'method' => 'POST',
        'form_name' => 'create',
        'formfields_template' => 'pages.admin.formfields',
        'formfields_data' => [
            'page' => $page,
            'errors' => $errors,
        ],
        'submit' => 'Create',
    ])
@endsection
