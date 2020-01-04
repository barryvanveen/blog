@presenter(App\Application\View\BaseViewPresenter)
<!doctype html>
<html lang="{{ $locale }}">
    @include('layouts.partials.headHtml')

    <body class="bg-gray-200">
        <main class="container mx-auto max-w-2xl px-4">
            @yield('body')
        </main>

        @include('layouts.partials.javascript')
    </body>
</html>
