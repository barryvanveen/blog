@php
/** @var \App\Domain\Articles\Models\Article $article */
@endphp

<li>
    <article>
        <h1>{{ $article->title() }}</h1>
        <p>{{ $article->publishedAt()->format('Y-m-d H:i:s') }}, 0 comments</p>
        <p>{{ $article->description() }}</p>
        <a href="{{ route('articles.show', ['uuid' => $article->uuid(), 'slug' => $article->slug()]) }}" class="read-article">Read article</a>
    </article>
</li>
