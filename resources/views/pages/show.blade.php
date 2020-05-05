@presenter(App\Application\Pages\View\PagesAboutPresenter)

@extends('layouts.base')

@section('headHtmlMetaTags')
    @include('layouts.partials.headHtmlMetaTags', ['metaData' => $metaData])
@endsection

@section('bgImage')
    <div class="-mt-16 block h-48 bg-repeat bg-image-1"></div>
@endsection

@section('bodyHeader')
    <article itemprop="mainEntity">
        <header class="-mt-32 bg-gray-100 p-8 pb-4">
            <h1 class="mt-0 mb-4" itemprop="name">
                {{ $title }}
            </h1>
            <p class="text-gray-700 text-sm">
                Last update: <time itemprop="lastReviewed" datetime="{{ $lastUpdatedDateInAtomFormat }}">{{ $lastUpdatedDateInHumanFormat }}</time>
                <span itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="hidden"><span itemprop="name">Barry van Veen</span></span>
            </p>
        </header>
@endsection

@section('body')
        <div itemprop="text">
            {!! $content !!}
        </div>

    </article>
@endsection
