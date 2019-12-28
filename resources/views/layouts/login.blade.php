@presenter(App\Application\View\BaseViewPresenter)
<!doctype html>
<html lang="{{ $locale }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ $csrf_token }}">
        <base href="{{ $base_url }}">

        <title>Home - A blog about web development - Barry van Veen</title>

        <link href="{{ $css_path }}" rel="stylesheet" type="text/css">
    </head>

    <body class="bg-gray-200">
        <main class="container mx-auto max-w-2xl px-4">
            @yield('body')
        </main>
        <script src="{{ $js_path }}"></script>
    </body>
</html>
