@presenter(App\Application\View\BaseViewPresenter)
<!doctype html>
<html lang="{{ $locale }}">
    @include('layouts.partials.headHtml')

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
