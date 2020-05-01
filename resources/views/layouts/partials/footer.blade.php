@presenter(\App\Application\View\FooterPresenter)
<footer class="w-full mt-16 bg-gray-700 text-white">
    <div class="container mx-auto max-w-3xl px-4 py-8 flex-none sm:flex items-center justify-center">

        @foreach($menu_items as $menu_item)
            <a class="pr-8 align-items-center"
               href="{{ $menu_item->url() }}" @if($menu_item->openInNewWindow())target="_blank"@endif>
                {{ $menu_item->name() }}
            </a>
        @endforeach

    </div>
</footer>
