@presenter(App\Application\Pages\View\SitemapPresenter)

@extends('layouts.sitemap')

@section('items')
    @foreach($items as $item)
        <url>
            <loc>{{ $item['url'] }}</loc>
            <lastmod>{{ $item['lastmod'] }}</lastmod>
        </url>
    @endforeach
@endsection
