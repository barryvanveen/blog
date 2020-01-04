@presenter(App\Application\View\HeadHtmlPresenter)
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ $csrf_token }}">
    <base href="{{ $base_url }}">

    <title>@yield('title') - A blog about web development - Barry van Veen</title>

    <link href="{{ $css_path }}" rel="stylesheet" type="text/css">
</head>
