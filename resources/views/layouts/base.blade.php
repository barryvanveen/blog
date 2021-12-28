<!doctype html>
<html lang="en">
@include('layouts.partials.headHtml')

<body class="min-h-screen flex flex-col">
    <div class="grow-0">
        @include('layouts.partials.header')
    </div>

    <div class="flex flex-grow pt-8">
        <main role="main">
            <div class="container-max-md pb-8">
                @yield('body')
            </div>
            <div class="bg-lightTeal">
                <div class="container-max-md">
                    @yield('comments')
                </div>
            </div>
        </main>
    </div>

    @include('layouts.partials.javascript')
    @include('layouts.partials.mobileHeader')
</body>
</html>
