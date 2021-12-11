@presenter(\App\Application\View\FooterPresenter)
<footer class="w-full py-6 border-t-4 border-color">
    <div class="container-max-md md:flex md:justify-between md:items-center">
        <div class="hidden md:block ">
            @include('layouts.partials.logo')
        </div>
        <nav>
            <ul class="w-full md:w-auto justify-center md:justify-end inline-flex gap-x-8">
                @foreach($menu_items as $menu_item)
                    <li>
                        <a class="hover-link-color"
                           href="{{ $menu_item->url() }}" @if($menu_item->openInNewWindow())target="_blank" rel="noopener"@endif>
                            {{ $menu_item->name() }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

    </div>
</footer>
