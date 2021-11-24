<!doctype html>
<html lang="en">
    @include('layouts.partials.headHtml')

    <body>
        <main class="container-max-md mx-auto max-w-2xl px-4">
            @yield('body')
        </main>

        @include('layouts.partials.javascript')
    </body>
</html>
