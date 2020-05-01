<!doctype html>
<html lang="en">
    @include('layouts.partials.headHtml')

    <body>
        @include('layouts.partials.header')

        @yield('bgImage')
        <main>
            <div class="container mx-auto max-w-4xl px-4 md:px-12">
                @yield('bodyHeader')
            </div>
            <div class="container mx-auto max-w-3xl px-4">
                @yield('body')
            </div>
        </main>

        @yield('related')

        @include('layouts.partials.footer')
        @include('layouts.partials.javascript')
    </body>
</html>
