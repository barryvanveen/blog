@presenter(App\Application\Articles\View\ArticlesRssPresenter)

<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">
    <channel>
        <title>{{ $title }}</title>
        <link>{{ $site_url }}</link>
        <description>{{ $description }}</description>
        <atom:link href="{{ $rss_url }}" rel="self"/>
        <language>en</language>
        <lastBuildDate>{{ $last_modified }}</lastBuildDate>

        @yield('items')
    </channel>
</rss>
