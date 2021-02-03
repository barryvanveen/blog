@presenter(App\Application\View\HeadHtmlPresenter)
<head>
    <meta name="robots" content="none" />

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ $csrf_token }}">
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
</head>
