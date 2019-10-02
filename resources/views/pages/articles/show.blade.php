@presenter(App\Application\Articles\View\ArticlesItemPresenter)

@extends('layouts.base')

@section('body')

    <article>
        <h1>{{ $article->title() }}</h1>
        <p>{{ $article->publishedAt()->format('Y-m-d H:i:s') }}, 0 comments</p>
        <p>{{ $article->content() }}</p>
    </article>

@endsection
