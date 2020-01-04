@presenter(App\Application\Articles\View\ArticlesIndexPresenter)

@extends('layouts.base')

@section('title', 'Articles')

@section('body')
    <section itemscope itemtype="https://schema.org/Blog">
        <h1 itemprop="about">Articles</h1>

        @foreach($articles as $article)
            <article itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
                <header class="mb-4">
                    <h2 class="" itemprop="headline">
                        <a href="{{ route('articles.show', ['uuid' => $article->uuid(), 'slug' => $article->slug()]) }}"
                           itemprop="url">
                            {{ $article->title() }}
                        </a>
                    </h2>
                </header>
                <div itemprop="description">
                    <p>{{ $article->description() }}</p>
                </div>
            </article>
        @endforeach
    </section>
@endsection
