@presenter(App\Application\Pages\View\AdminPagesEditPresenter)
@extends('layouts.base')

@section('title', $title)

@section('body')
    @include('partials.admin.create_or_update', [
        'title' => $title,
        'url' => $url,
        'method' => 'PUT',
        'form_name' => 'edit',
        'formfields_template' => 'pages.admin.formfields',
        'formfields_data' => [
            'page' => $page,
            'errors' => $errors,
        ],
        'submit' => 'Edit',
    ])
@endsection
