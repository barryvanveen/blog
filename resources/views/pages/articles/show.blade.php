@presenter(App\Application\Articles\View\ArticlesItemPresenter)

@extends('layouts.base')

@section('body')

    <article itemprop="mainEntity" itemscope="" itemtype="https://schema.org/BlogPosting">
        <header>
            <h1 itemprop="headline">
                {{ $title }}
            </h1>
            <p>
                <time datetime="{{ $publicationDateInAtomFormat }}" itemprop="datePublished">{{ $publicationDateInHumanFormat }}</time>
                &diamond; <span itemprop="author" itemscope="" itemtype="http://schema.org/Person"><span itemprop="name">Barry van Veen</span></span>
                &diamond; <a href="/whiteglass/categories/junk/">junk</a>
            </p>
        </header>
        <div itemprop="articleBody">
            {!! $content !!}
        </div>
    </article>

@endsection

@section('related')

@endsection
