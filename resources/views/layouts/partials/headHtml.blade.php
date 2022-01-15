@presenter(App\Application\View\HeadHtmlPresenter)
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <base href="{{ $base_url }}">

    <meta name="site_name" content="Barry van Veen" />
    <meta property="og:site_name" content="Barry van Veen" />
    <meta name="locale" content="en_EN" />
    <meta property="og:locale" content="en_EN" />
    @yield('headHtmlMetaTags')

    <link rel="author" href="{{ $about_url }}">
    @foreach($css_paths as $path)
        <link href="{{ $path }}" rel="stylesheet" type="text/css">
    @endforeach
    <link href="{{ $rss_url }}" rel="alternate" type="application/rss+xml" title="Barry van Veen's blog">

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=2">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=2">
    <link rel="icon" href="favicon.svg">
    <link rel="manifest" href="/site.webmanifest?v=2">
    <link rel="mask-icon" href="/monochrome-icon.svg?v=2" color="#971a20">
    <meta name="msapplication-TileColor" content="#f4c148">
    <meta name="theme-color" content="#f4c148">
</head>
