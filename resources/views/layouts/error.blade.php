@presenter(App\Application\View\BaseViewPresenter)
<!doctype html>
<html lang="{{ $locale }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <base href="{{ $base_url }}">

        <title>@yield('title') - A blog about web development - Barry van Veen</title>

        <link href="{{ $css_path }}" rel="stylesheet" type="text/css">
    </head>

    <body class="bg-white">
        @include('layouts.partials.header')

        <div class="flex items-center justify-center text-gray-700 py-10">
            <div class="border-r-2 px-6 text-3xl text-center">
                @yield('code')
            </div>

            <div class="text-2xl px-4 text-2xl text-center">
                @yield('message')
            </div>
        </div>

        @include('layouts.partials.footer')
    </body>
</html>
