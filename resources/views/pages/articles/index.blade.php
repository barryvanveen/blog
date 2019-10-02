@presenter(App\Application\Articles\View\ArticlesIndexPresenter)

@extends('layouts.base')

@section('body')
    <section>
        <h1 id="title">Articles</h1>

        <ul>
            @each('blocks.articles.partials.card', $articles, 'article')
        </ul>
    </section>
@endsection
