<!doctype html>
<html lang="en">
@include('layouts.partials.headHtml')

<body class="min-h-screen flex flex-col">
    <div class="grow-0">
        @include('layouts.partials.header')
    </div>

    <div class="flex flex-grow pt-8">
        <main role="main">
            <div class="container pb-8">
                @yield('body')
            </div>
        </main>
    </div>

    @include('layouts.partials.javascript')
    @include('layouts.partials.mobileHeader')
</body>
</html>
