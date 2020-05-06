@presenter(App\Application\Articles\View\ArticlesRssItemPresenter)

@extends('layouts.rss')

@section('items')
    @foreach($articles as $article)
        <item>
            <title>{{ $article['title'] }}</title>
            <link>{{ $article['url'] }}</link>
            <guid isPermaLink="true">{{ $article['url'] }}</guid>
            <description>{{ $article['description'] }}</description>
            <pubDate>{{ $article['publicationDate'] }}</pubDate>
        </item>
    @endforeach
@endsection
