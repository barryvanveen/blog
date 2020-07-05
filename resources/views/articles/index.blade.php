@presenter(App\Application\Articles\View\ArticlesIndexPresenter)

@extends('layouts.base')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('body')
    <section itemscope itemtype="https://schema.org/Blog">
        <h1 itemprop="about">Articles</h1>

        @foreach($articles as $article)
            <article class="w-full max-w-full sm:flex mb-8"
                     itemprop="blogPost" itemscope itemtype="https://schema.org/BlogPosting">

                <a class="block h-24 sm:h-auto sm:w-48 flex-none bg-repeat rounded-t sm:rounded-t-none sm:rounded-l text-center overflow-hidden bg-image-{{ ($loop->iteration % 5) + 1 }}"
                   href="{{ $article['url'] }}" itemprop="url">
                </a>

                <div class="border-r border-b border-l border-gray-400 sm:border-l-0 sm:border-t sm:border-gray-400 bg-white rounded-b sm:rounded-b-none sm:rounded-r p-6 flex flex-col justify-between leading-normal">
                    <header>
                        <h2 itemprop="headline" class="text-base leading-normal my-0">
                            <a class="text-gray-900 font-bold text-xl mb-2 no-underline"
                               href="{{ $article['url'] }}" itemprop="url">
                                {{ $article['title'] }}
                            </a>
                        </h2>
                        <p class="text-gray-700 text-base mb-0" itemprop="description">
                            {!! $article['description'] !!}
                        </p>
                    </header>
                </div>
            </article>
        @endforeach
    </section>
@endsection
