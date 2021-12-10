<!doctype html>
<html lang="en">
    @include('layouts.partials.headHtml')

    <body class="min-h-screen flex flex-col">
        <div class="flex-grow-0">
            @include('layouts.partials.header')
        </div>

        <div class="flex-grow">
            <div class="container-max-md pt-8">
                <div class="text-center">
                    <span class="border-r-2 px-6 text-4xl">
                        @yield('code')
                    </span>

                    <span class="text-3xl px-6">
                        @yield('message')
                    </span>
                </div>
            </div>
        </div>

        <div class="flex-grow-0">
            @include('layouts.partials.footer')
        </div>

        @include('layouts.partials.javascript')
        @include('layouts.partials.mobileHeader')
    </body>
</html>
