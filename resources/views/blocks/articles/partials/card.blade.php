@php
/** @var \App\Domain\Articles\Models\Article $article */
@endphp

<div>
    <p>{{ $article->title() }}</p>
    <p>{{ $article->publishedAt()->format('Y-m-d H:i:s') }}, 0 comments</p>
    <p>{{ $article->description() }}</p>
</div>