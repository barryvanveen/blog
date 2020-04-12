@presenter(App\Application\Articles\View\ArticlesIndexPresenter)

@extends('layouts.base')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('body')
    <section itemscope itemtype="https://schema.org/Blog">
        <h1 itemprop="about">Articles</h1>

        @foreach($articles as $article)
            <article itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
                <header class="mb-4">
                    <h2 class="" itemprop="headline">
                        <a href="{{ $article['url'] }}" itemprop="url">
                            {{ $article['title'] }}
                        </a>
                    </h2>
                </header>
                <div itemprop="description">
                    <p>{{ $article['description'] }}</p>
                </div>
            </article>
        @endforeach
    </section>
@endsection
