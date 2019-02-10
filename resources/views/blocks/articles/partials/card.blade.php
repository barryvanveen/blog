@php
/** @var \App\Domain\Articles\Models\Article $article */
@endphp

<div>
    <p>{{ $article->title }}</p>
    <p>{{ $article->published_at }}, 0 comments</p>
    <p>{{ $article->description }}</p>
</div>