@presenter(App\Application\Pages\View\PagesHomePresenter)

@extends('layouts.base')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('body')
    <article itemprop="mainEntity">
        <header>
            <h1 itemprop="name">
                {{ $title }}
            </h1>
        </header>

        <div itemprop="text">
            {!! $content !!}
        </div>

        <span class="divider"></span>

        <footer class="my-6">
            <p class="article-details">
                Last update: <time itemprop="lastReviewed" datetime="{{ $lastUpdatedDateInAtomFormat }}">{{ $lastUpdatedDateInHumanFormat }}</time>
                <span itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="hidden"><span itemprop="name">Barry van Veen</span></span>
            </p>
        </footer>
    </article>
@endsection
