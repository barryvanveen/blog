@presenter(App\Application\Articles\View\ArticlesItemPresenter)

@extends('layouts.base')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('body')
    <article class="" itemprop="mainEntity" itemscope="" itemtype="https://schema.org/BlogPosting">
        <header class="my-4">
            <h1 itemprop="headline">
                {{ $title }}
            </h1>
            <p class="article-details">
                <time datetime="{{ $publicationDateInAtomFormat }}" itemprop="datePublished">{{ $publicationDateInHumanFormat }}</time>
                <span itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="hidden"><span itemprop="name">Barry van Veen</span></span>
            </p>
        </header>

        <div itemprop="articleBody">
            {!! $content !!}
        </div>

    </article>
@endsection

@section('related')

@endsection

@section('comments')
    @include('comments.comments')
@endsection
