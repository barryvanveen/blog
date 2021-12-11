@presenter(App\Application\Articles\View\ArticlesIndexPresenter)

@extends('layouts.base')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('body')
    <section itemscope itemtype="https://schema.org/Blog">
        <h1 itemprop="about" class="mb-8">Articles</h1>

        @foreach($articles as $article)
            <article class="pt-6 mb-8 border-t-4 border-color" itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">
                <header>
                    <h2 itemprop="headline" class="mt-0 mb-2 text-2xl hover-link-color">
                        <a class="no-underline text-gray-900 hover-link-color hover:underline"
                           href="{{ $article['url'] }}" itemprop="url">
                            {{ $article['title'] }}
                        </a>
                    </h2>
                </header>
                <section class="text-base mb-2 hidden sm:block" itemprop="description">
                    {!! $article['description'] !!}
                </section>
                <section class="article-details">
                    <time class="mr-6" datetime="{{ $article['publication_date_meta']  }}">{{ $article['publication_date'] }}</time>
                    {{ $article['comments'] }} comments
                </section>
            </article>
        @endforeach
    </section>
@endsection
