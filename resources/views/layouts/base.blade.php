@presenter(App\Application\View\BaseViewPresenter)
<!doctype html>
<html lang="{{ $locale }}">
    @include('layouts.partials.headHtml')

    <body class="bg-white">
        @include('layouts.partials.header')

        <main class="container mx-auto max-w-2xl px-4">
            @yield('body')
        </main>

        @yield('related')

        @include('layouts.partials.footer')
        <script src="{{ $js_path }}"></script>
    </body>
</html>
