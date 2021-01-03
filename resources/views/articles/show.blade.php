@presenter(App\Application\Articles\View\ArticlesItemPresenter)

@extends('layouts.base')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('bgImage')
    <div class="-mt-16 block h-48 bg-repeat bg-image-1"></div>
@endsection

@section('bodyHeader')
    <article class="" itemprop="mainEntity" itemscope="" itemtype="https://schema.org/BlogPosting">
        <header class="-mt-32 bg-gray-100 p-8 pb-4">
            <h1 class="mt-0 mb-4" itemprop="headline">
                {{ $title }}
            </h1>
            <p class="text-gray-700 text-sm">
                <time datetime="{{ $publicationDateInAtomFormat }}" itemprop="datePublished">{{ $publicationDateInHumanFormat }}</time>
                <span itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="hidden"><span itemprop="name">Barry van Veen</span></span>
            </p>
        </header>
@endsection

@section('body')
        <div itemprop="articleBody">
            {!! $content !!}
        </div>
    </article>

    @include('comments.comments')
@endsection

@section('related')

@endsection
