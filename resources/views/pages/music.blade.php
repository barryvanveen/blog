@presenter(App\Application\Pages\View\PagesMusicPresenter)

<?php /** @var string $title */ ?>
<?php /** @var string $content */ ?>
<?php /** @var \App\Domain\Utils\MetaData $metaData */ ?>
<?php /** @var \App\Domain\Music\Album[] $albums */ ?>

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

        @if($albums)
            <ul class="list-none pl-0 my-6 flex flex-col">
                @foreach($albums as $album)
                    <li class="mb-3">
                        @if($album->image())
                            <img src="{{ $album->image() }}" class="w-16 h-16 float-left mr-3" alt="Cover image for {{ $album->name() }} by {{ $album->artist() }}">
                        @else
                            <div class="w-16 h-16 float-left mr-3 shadow-md">
                                <div class="w-full h-full overflow-hidden text-center bg-turmeric table cursor-pointer">
                                    <span class="table-cell text-lg font-bold align-middle">?</span>
                                </div>
                            </div>
                        @endif
                        <span class="font-bold">{{ $album->artist() }}</span><br>
                        {{ $album->name() }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="italic">{{ __('blog.no_music_found') }}</p>
        @endif

        <span class="divider"></span>

        <footer class="my-6">
            <p class="article-details">
                Last update: <time itemprop="lastReviewed" datetime="{{ $lastUpdatedDateInAtomFormat }}">{{ $lastUpdatedDateInHumanFormat }}</time>
                <span itemprop="author" itemscope="" itemtype="http://schema.org/Person" class="hidden"><span itemprop="name">Barry van Veen</span></span>
            </p>
        </footer>
    </article>
@endsection
